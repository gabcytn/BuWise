<?php

namespace App\Jobs;

use App\Models\InvoiceLine;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Transaction;
use App\Models\User;
use App\Services\InvoiceStore;
use App\Services\Llmwhisperer;
use App\Services\OpenAi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScanInvoiceInWeb implements ShouldQueue
{
    use Queueable;

    private float $tax_total;
    private float $discount_total;
    private float $purchases_total;
    private float $net;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private User $accountant,
        private User $client,
        private string $filename,
        private string $transaction_type
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $helper = new Llmwhisperer($this->filename);
        $output_text = $helper->extract();
        if ($output_text === '') {
            // TODO: handle error
            Log::error('LLMWhisperer returned an empty string');
            return;
        }

        $json_text = $this->structureTexts($output_text);

        try {
            DB::beginTransaction();
            $items = $this->getItems($json_text);
            $invoice = $this->createTransaction($json_text);
            $this->createInvoiceLines($items, $invoice);
            switch ($this->transaction_type) {
                case 'sales':
                    $this->ledgerEntryForSales($invoice, $json_text);
                    break;
                case 'purchases':
                    $this->ledgerEntryForPurchases($invoice, $json_text);
                    break;
                default:
                    break;
            }
            DB::commit();
            $fileContents = Storage::disk('public')->get('temp/' . $this->filename);
            Storage::disk('s3')->put('invoices/' . $this->filename, $fileContents);
            Storage::disk('public')->delete('invoices/' . $this->filename);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Log::error(truncate($e->getTraceAsString(), 200));
        }
    }

    private function structureTexts($text)
    {
        $open_ai = new OpenAi($text);
        $json_text = $open_ai->prompt();
        $json_text = json_decode($json_text, true);
        return $json_text;
    }

    private function getItems($data)
    {
        $validated = [];
        $items = $data['items'];
        $discount_total = 0;
        $tax_total = 0;
        $net_total = 0;
        $purchases_total = 0;
        // get net total w/o discount & tax
        foreach ($items as $item) {
            $unitPrice = round((float) $item['unitPrice'], 2);
            $qty = (int) $item['quantity'];
            $net = round($unitPrice * $qty, 2);

            $net_total += $net;
        }

        $whole_discount_percentage = 0.0;
        $whole_tax_percentage = 0.0;
        if ($data['discount'] > 0.0)
            $whole_discount_percentage =
                round($data['discount'], 2) / round($net_total, 2);
        if ($data['tax'] > 0.0)
            $whole_tax_percentage =
                round($data['tax'], 2) / round($net_total - $data['discount'], 2);

        foreach ($items as $item) {
            $unitPrice = round((float) $item['unitPrice'], 2);
            $discount = round((float) $item['discount'], 2);
            $tax = round((float) $item['tax'], 2);

            if ($whole_discount_percentage > 0.0) {
                $discount += ($whole_discount_percentage * $unitPrice);
                $item['discount'] = $discount;
            }
            $discountedUnitPrice = $unitPrice - $discount;
            if ($whole_tax_percentage > 0.0) {
                $tax += ($whole_tax_percentage * $discountedUnitPrice);
                $item['tax'] = $tax;
            }

            $qty = (int) $item['quantity'];
            $tax_total += $tax * $qty;
            $discount_total += $discount * $qty;

            $purchasesNet = round(($discountedUnitPrice + $tax) * $qty, 2);
            $purchases_total += $purchasesNet;

            $validated[] = $item;
        }

        $this->tax_total = $tax_total;
        $this->discount_total = $discount_total;
        $this->net = $net_total;
        $this->purchases_total = $purchases_total;

        return $validated;
    }

    private function ledgerEntryForSales(Transaction $invoice, $data)
    {
        if ($this->tax_total > 0) {
            $taxEntry = LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::OUTPUT_VAT_PAYABLE,
                'entry_type' => 'credit',
                'amount' => $this->tax_total
            ]);
        }
        if ($this->discount_total > 0) {
            LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::SALES_DISCOUNT,
                'entry_type' => 'debit',
                'tax_ledger_entry_id' => null,
                'amount' => $this->discount_total,
            ]);
        }
        LedgerEntry::insert([
            [
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::SALES,
                'entry_type' => 'credit',
                'tax_ledger_entry_id' => $this->tax_total > 0 ? $taxEntry->id : null,
                'amount' => $this->net - $this->tax_total,
            ],
            [
                'transaction_id' => $invoice->id,
                'account_id' => InvoiceStore::ACCOUNT_LOOKUP[$data['paymentMethod']],
                'entry_type' => 'debit',
                'tax_ledger_entry_id' => null,
                'amount' => $this->net - $this->discount_total,
            ]
        ]);
    }

    private function ledgerEntryForPurchases(Transaction $invoice, $data)
    {
        if ($this->tax_total > 0) {
            $taxEntry = LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::INPUT_VAT_RECEIVABLE,
                'entry_type' => 'debit',
                'amount' => $this->tax_total
            ]);
        }
        LedgerEntry::insert([
            [
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::GENERAL_EXPENSE,
                'entry_type' => 'debit',
                'tax_ledger_entry_id' => $this->tax_total > 0 ? $taxEntry->id : null,
                'amount' => $this->purchases_total - $this->tax_total,
            ],
            [
                'transaction_id' => $invoice->id,
                'account_id' => InvoiceStore::ACCOUNT_LOOKUP[$data['paymentMethod']],
                'entry_type' => 'credit',
                'tax_ledger_entry_id' => null,
                'amount' => $this->purchases_total,
            ]
        ]);
    }

    private function createTransaction($data)
    {
        return Transaction::create([
            'client_id' => $this->client->id,
            'created_by' => $this->accountant->id,
            'status' => 'approved',
            'type' => 'invoice',
            'kind' => $this->transaction_type,
            'amount' => $this->net,
            'date' => $data['issueDate'],
            'payment_method' => $data['paymentMethod'],
            'description' => $data['description'],
            'reference_no' => $data['invoiceId'],
            'image' => $this->filename,
        ]);
    }

    private function createInvoiceLines(array $items, Transaction $invoice)
    {
        foreach ($items as $item) {
            InvoiceLine::create([
                'invoice_id' => $invoice->id,
                'tax' => $item['tax'],
                'item_name' => $item['itemName'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unitPrice'],
                'discount' => $item['discount']
            ]);
        }
    }
}
