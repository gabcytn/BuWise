<?php
namespace App\Services;

use App\Models\InvoiceLine;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Transaction;
use App\Services\InvoiceStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceUpdate
{
    private float $tax_total;
    private float $discount_total;
    private float $net;
    private float $purchases_net;
    private float $withholding_tax;

    public function __construct(
        private Request $request,
        private Transaction $invoice
    ) {
        $this->withholding_tax = $request->withholding_tax;
    }

    public function update()
    {
        $invoice = $this->invoice;
        $request = $this->request;

        try {
            DB::beginTransaction();
            $invoice->date = $request->issue_date;
            $invoice->reference_no = $request->invoice_number;
            $invoice->description = $request->description;
            $invoice->payment_method = $request->payment_method;
            $invoice->withholding_tax = $request->withholding_tax;

            $helper = new InvoiceStore($request);
            $rowNumbers = $helper->getRowNumbers($request);
            $arr = $helper->getInvoiceItems($request, $rowNumbers);
            $this->tax_total = $arr['taxTotal'];
            $this->discount_total = $arr['discountTotal'];
            $this->net = $arr['amountTotal'];
            $this->purchases_net = $arr['purchasesTotal'];

            $invoice->amount = $this->net;
            $invoice->save();

            $items = $arr['items'];
            $invoice_lines = InvoiceLine::where('invoice_id', '=', $invoice->id)->get();
            foreach ($invoice_lines as $idx => $invoice_line) {
                $invoice_line->item_name = $items[$idx]['item_name'];
                $invoice_line->quantity = $items[$idx]['qty'];
                $invoice_line->unit_price = $items[$idx]['unit_price'];
                $invoice_line->discount = $items[$idx]['discount'];
                $invoice_line->tax = $items[$idx]['tax'];
                $invoice_line->save();
            }

            $entries = LedgerEntry::where('transaction_id', '=', $invoice->id)->get();
            switch ($invoice->kind) {
                case 'sales':
                    $this->ledgerEntryForSales($entries, $invoice);
                    break;
                case 'purchases':
                    $this->ledgerEntryForPurchases($entries, $invoice);
                    break;
                default:
                    break;
            }
            DB::commit();
            return redirect()->back()->with('status', 'Successfully updated invoice');
        } catch (\Exception $e) {
            Log::info('Error updating invoice: ' . $e->getMessage());
            Log::info(truncate($e->getTraceAsString(), 500));
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update']);
        }
    }

    private function ledgerEntryForSales($entries, Transaction $invoice)
    {
        $tax_entry = $entries->firstWhere('account_id', '=', LedgerAccount::OUTPUT_VAT_PAYABLE);
        if ($tax_entry && $this->tax_total > 0.0) {
            // if old tax exists and new tax > 0, change old tax
            $tax_entry->amount = $this->tax_total;
            $tax_entry->save();
        } else if (!$tax_entry && $this->tax_total > 0.0) {
            // if old tax doesn't exist and new tax > 0, create new tax
            $tax_entry = LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::OUTPUT_VAT_PAYABLE,
                'entry_type' => 'credit',
                'amount' => $this->tax_total
            ]);
        } else if ($tax_entry && $this->tax_total === 0.0) {
            // if old tax exists and new tax is 0, delete old tax
            $tax_entry->delete();
        }

        $discount_entry = $entries->firstWhere('account_id', '=', LedgerAccount::SALES_DISCOUNT);
        if ($discount_entry && $this->discount_total > 0.0) {
            // if discount entry exists and current discount > 0, update existing entry
            $discount_entry->amount = $this->discount_total;
            $discount_entry->save();
        } elseif (!$discount_entry && $this->discount_total > 0.0) {
            // if discount entry doesn't exist but current discount > 0, create new entry
            $discount_entry = LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::SALES_DISCOUNT,
                'entry_type' => 'debit',
                'tax_ledger_entry_id' => null,
                'amount' => $this->discount_total,
            ]);
        } else if ($discount_entry && $this->discount_total === 0.0) {
            // if discount entry exists and current discount is 0, delete previous
            $discount_entry->delete();
        }

        $withholding_tax_account = LedgerAccount::where('code', '=', 106)->where('name', '=', 'Withholding Tax Receivable')->first();
        $withholding_tax_entry = $entries->firstWhere('account_id', '=', $withholding_tax_account->id);
        if ($withholding_tax_entry && $this->withholding_tax > 0.0) {
            // if withholding entry exists and current withholding > 0, update existing entry
            $withholding_tax_entry->amount = $this->withholding_tax;
            $withholding_tax_entry->save();
        } elseif (!$withholding_tax_entry && $this->withholding_tax > 0.0) {
            // if withholding entry doesn't exist but current withholding > 0, create new entry
            LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => $withholding_tax_account->id,
                'entry_type' => 'debit',
                'amount' => $this->withholding_tax,
            ]);
        } else if ($withholding_tax_entry && $this->withholding_tax === 0.0) {
            // if withholding entry exists and current withholding is 0, delete previous
            $withholding_tax_entry->delete();
        }
        $sales_entry = $entries->firstWhere('account_id', '=', LedgerAccount::SALES);
        $sales_entry->amount = $this->net - $this->tax_total;
        $sales_entry->save();

        $debit_entry = $entries->whereNotIn('account_id', [
            LedgerAccount::SALES,
            LedgerAccount::OUTPUT_VAT_PAYABLE,
            LedgerAccount::SALES_DISCOUNT
        ])->first();
        $debit_entry->amount = $this->net - $this->discount_total - $this->withholding_tax;
        $debit_entry->account_id = InvoiceStore::ACCOUNT_LOOKUP[$this->request->payment_method];
        $debit_entry->save();
    }

    private function ledgerEntryForPurchases($entries, Transaction $invoice)
    {
        $tax_entry = $entries->firstWhere('account_id', '=', LedgerAccount::INPUT_VAT_RECEIVABLE);
        if ($tax_entry && $this->tax_total > 0.0) {
            // if old tax exists and new tax > 0, change old tax
            $tax_entry->amount = $this->tax_total;
            $tax_entry->save();
        } elseif (!$tax_entry && $this->tax_total > 0.0) {
            // if old tax doesn't exist and new tax > 0, create new tax
            $tax_entry = LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::INPUT_VAT_RECEIVABLE,
                'entry_type' => 'debit',
                'amount' => $this->tax_total,
            ]);
        } elseif ($tax_entry && $this->tax_total === 0.0) {
            // if old tax exists and new tax is 0, delete old tax
            $tax_entry->delete();
        }

        $expense_entry = $entries->firstWhere('account_id', '=', LedgerAccount::GENERAL_EXPENSE);
        $expense_entry->amount = $this->purchases_net - $this->tax_total;
        $expense_entry->save();

        $credit_entry = $entries->whereNotIn('account_id', [
            LedgerAccount::GENERAL_EXPENSE,
            LedgerAccount::INPUT_VAT_RECEIVABLE,
        ])->first();
        $credit_entry->amount = $this->purchases_net;
        $credit_entry->account_id = InvoiceStore::ACCOUNT_LOOKUP[$this->request->payment_method];
        $credit_entry->save();
    }
}
