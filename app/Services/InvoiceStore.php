<?php
namespace App\Services;

use App\Models\EntryType;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceStore
{
    private float $discountTotal;
    private float $taxTotal;
    private float $amountTotal;

    public function __construct(
        private Request $request,
    ) {}

    public function store()
    {
        $request = $this->request;
        try {
            DB::beginTransaction();

            $filename = (string) Str::uuid() . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $rowNumbers = $this->getRowNumbers($request);
            $items = $this->getInvoiceItems($request, $rowNumbers);
            $invoice = $this->creatInvoice($filename);
            $this->createInvoiceLines($items, $invoice);

            switch ($request->transaction_type) {
                case 'sales':
                    $this->ledgerEntryForSales($invoice);
                    break;
                case 'purchases':
                    dd('here');
                    $this->ledgerEntryForPurchases($invoice);
                    break;
                default:
                    throw new \Exception('Invalid transaction type');
                    break;
            }

            $request->file('image')->storeAs('invoices/', $filename, 's3');

            DB::commit();
            return redirect()
                ->back()
                ->with([
                    'status' => 'Invoice created successfully.'
                ]);
        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            Log::error(truncate($e->getTraceAsString(), 100));
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors([
                    'message' => 'Invoice creation failed.'
                ]);
        }
    }

    private function creatInvoice(string $filename)
    {
        $request = $this->request;
        $invoice = Transaction::create([
            'client_id' => $request->client,
            'created_by' => $this->request->user()->id,
            'status' => 'approved',
            'type' => 'invoice',
            'kind' => $request->transaction_type,
            'amount' => $this->amountTotal,
            'date' => $request->issue_date,
            'payment_method' => $request->payment_method,
            'reference_no' => $request->invoice_number,
            'description' => $request->description,
            'image' => $filename,
        ]);
        return $invoice;
    }

    private function getInvoiceItems(Request $request, array $rowNumbers): array
    {
        $items = [];

        $discountTotal = 0;
        $taxTotal = 0;
        $netTotal = 0;
        foreach ($rowNumbers as $rowNumber) {
            $item = [
                'item_name' => $request->input("item_$rowNumber"),
                'qty' => $request->input("qty_$rowNumber"),
                'unit_price' => $request->input("unit_price_$rowNumber"),
                'discount' => $request->input("discount_$rowNumber", '0'),
                'tax' => $request->input("tax_$rowNumber", '0'),
            ];

            $taxModel = Tax::find((int) $item['tax']);

            $baseTotal = (float) $item['unit_price'] * (float) $item['qty'];
            $discount = ((float) $item['discount'] / 100) * $baseTotal;
            $discountedTotal = $baseTotal - $discount;
            if ($taxModel) {
                $tax = ((float) $taxModel->value / 100) * $discountedTotal;
            } else {
                $tax = 0;
            }
            $net = $discountedTotal + $tax;

            $discountTotal += $discount;
            $taxTotal += $tax;
            $netTotal += $net;

            $items[] = $item;
        }
        $this->discountTotal = $discountTotal;
        $this->taxTotal = $taxTotal;
        $this->amountTotal = $netTotal;
        return $items;
    }

    private function getRowNumbers(Request $request): array
    {
        $arr = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'item_') === 0) {
                $arr[] = substr($key, 5);
            }
        }

        return $arr;
    }

    private function createInvoiceLines(array $items, Transaction $invoice)
    {
        foreach ($items as $item) {
            InvoiceLine::create([
                'invoice_id' => $invoice->id,
                'tax_id' => (int) $item['tax'] !== 0 ? $item['tax'] : null,
                'item_name' => $item['item_name'],
                'quantity' => $item['qty'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount']
            ]);
        }
    }

    private function ledgerEntryForSales(Transaction $invoice)
    {
        LedgerEntry::create([
            'transaction_id' => $invoice->id,
            'account_id' => LedgerAccount::SALES,
            'entry_type_id' => EntryType::LOOKUP['credit'],
            'amount' => $this->amountTotal - $this->taxTotal,
        ]);
        $taxEntry = LedgerEntry::create([
            'transaction_id' => $invoice->id,
            'account_id' => LedgerAccount::OUTPUT_VAT_PAYABLE,
            'entry_type_id' => EntryType::LOOKUP['credit'],
            'amount' => $this->taxTotal
        ]);
        LedgerEntry::create([
            'transaction_id' => $invoice->id,
            'account_id' => 5,  // NOTE: temporarily stored in "Accounts Receivable" account
            'entry_type_id' => EntryType::LOOKUP['debit'],
            'tax_ledger_entry_id' => $taxEntry->id,
            'amount' => $this->amountTotal,
        ]);
    }

    private function ledgerEntryForPurchases()
    {
        // TODO: create a tax receivable account in COA
    }
}
