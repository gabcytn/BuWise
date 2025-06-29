<?php
namespace App\Services;

use App\Exceptions\InvoiceCreationException;
use App\Models\InvoiceLine;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InvoiceStore
{
    private float $discountTotal;
    private float $taxTotal;
    private float $amountTotal;
    private float $purchasesTotal;

    public const ACCOUNT_LOOKUP = [
        'cash' => LedgerAccount::CASH,
        'checkings' => LedgerAccount::CHECKINGS,
        'savings' => LedgerAccount::SAVINGS,
        'bank' => LedgerAccount::SAVINGS,
        'petty_cash' => LedgerAccount::PETTY_CASH,
        'receivable' => LedgerAccount::ACCOUNTS_RECEIVABLE,
        'payable' => LedgerAccount::ACCOUNTS_PAYABLE,
    ];

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
            $arr = $this->getInvoiceItems($request, $rowNumbers);
            $this->discountTotal = $arr['discountTotal'];
            $this->taxTotal = $arr['taxTotal'];
            $this->amountTotal = $arr['amountTotal'];
            $this->purchasesTotal = $arr['purchasesTotal'];
            $invoice = $this->createInvoice($filename);
            $this->createInvoiceLines($arr['items'], $invoice);

            switch ($request->transaction_type) {
                case 'sales':
                    $this->ledgerEntryForSales($invoice);
                    break;
                case 'purchases':
                    $this->ledgerEntryForPurchases($invoice);
                    break;
                default:
                    throw new InvoiceCreationException('Invalid transaction type');
                    break;
            }

            $request->file('image')->storeAs('invoices/', $filename, 's3');

            DB::commit();
            return redirect()
                ->back()
                ->with(['status' => 'Invoice created successfully.']);
        } catch (InvoiceCreationException $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            Log::error(truncate($e->getTraceAsString(), 100));
            DB::rollBack();
            return $this->returnErrorWithMessage('Invoice items contain invalid values.');
        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            Log::error(truncate($e->getTraceAsString(), 100));
            DB::rollBack();
            return $this->returnErrorWithMessage($e->getMessage());
        }
    }

    private function createInvoice(string $filename)
    {
        $request = $this->request;
        $invoice = Transaction::create([
            'client_id' => $request->client,
            'created_by' => $request->user()->id,
            'status' => $this->request->boolean('pending') ? 'pending' : 'approved',
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

    public function getInvoiceItems(Request $request, array $rowNumbers): array
    {
        $items = [];
        $discountTotal = 0;
        $taxTotal = 0;
        $netTotal = 0;
        $purchasesTotal = 0;
        foreach ($rowNumbers as $rowNumber) {
            $item = [
                'item_name' => $request->input("item_$rowNumber"),
                'qty' => $request->input("qty_$rowNumber"),
                'unit_price' => $request->input("unit_price_$rowNumber"),
                'discount' => $request->input("discount_$rowNumber", 0),
                'tax' => $request->input("tax_$rowNumber", 0),
            ];
            $this->validateInvoiceLines($item);

            $unitPrice = round((float) $item['unit_price'], 2);
            $discount = round((float) $item['discount'], 2);

            $tax = round((float) $item['tax'], 2);
            $qty = (int) $item['qty'];
            $net = round($unitPrice * $qty, 2);

            $discountTotal += $discount * $qty;
            $taxTotal += $tax * $qty;
            $netTotal += $net;

            $discountedUnitPrice = $unitPrice - $discount;
            $purchasesNet = round(($discountedUnitPrice + $tax) * $qty, 2);
            $purchasesTotal += $purchasesNet;

            $items[] = $item;
        }
        return [
            'items' => $items,
            'discountTotal' => $discountTotal,
            'taxTotal' => $taxTotal,
            'amountTotal' => $netTotal,
            'purchasesTotal' => $purchasesTotal,
        ];
    }

    private function validateInvoiceLines(array $item): void
    {
        $validator = Validator::make($item, [
            'item_name' => ['required', 'string', 'max:100'],
            'qty' => ['required', 'numeric', 'integer', 'gt:0'],
            'unit_price' => ['required', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'tax' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails())
            throw new InvoiceCreationException($validator->errors()->__toString());
    }

    public function getRowNumbers(Request $request): array
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
                'tax' => $item['tax'],
                'item_name' => $item['item_name'],
                'quantity' => $item['qty'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount']
            ]);
        }
    }

    private function ledgerEntryForSales(Transaction $invoice)
    {
        $withholding_tax = $this->request->withholding_tax ?: 0.0;
        if ($this->taxTotal > 0) {
            $taxEntry = LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::OUTPUT_VAT_PAYABLE,
                'entry_type' => 'credit',
                'amount' => $this->taxTotal
            ]);
        }
        if ($this->discountTotal > 0) {
            LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::SALES_DISCOUNT,
                'entry_type' => 'debit',
                'tax_ledger_entry_id' => null,
                'amount' => $this->discountTotal,
            ]);
        }
        if ($withholding_tax > 0.0) {
            $withholding_tax_account = LedgerAccount::where('code', '=', 106)
                ->where('name', '=', 'Withholding Tax Receivable')
                ->first();
            LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => $withholding_tax_account->id,
                'entry_type' => 'debit',
                'amount' => $withholding_tax,
            ]);
        }
        LedgerEntry::insert([
            [
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::SALES,
                'entry_type' => 'credit',
                'tax_ledger_entry_id' => $this->taxTotal > 0 ? $taxEntry->id : null,
                'amount' => $this->amountTotal - $this->taxTotal,
            ],
            [
                'transaction_id' => $invoice->id,
                'account_id' => self::ACCOUNT_LOOKUP[$this->request->payment_method],
                'entry_type' => 'debit',
                'tax_ledger_entry_id' => null,
                'amount' => $this->amountTotal - $this->discountTotal - $withholding_tax,
            ]
        ]);
    }

    private function ledgerEntryForPurchases(Transaction $invoice)
    {
        if ($this->taxTotal > 0) {
            $taxEntry = LedgerEntry::create([
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::INPUT_VAT_RECEIVABLE,
                'entry_type' => 'debit',
                'amount' => $this->taxTotal
            ]);
        }
        LedgerEntry::insert([
            [
                'transaction_id' => $invoice->id,
                'account_id' => LedgerAccount::GENERAL_EXPENSE,
                'entry_type' => 'debit',
                'tax_ledger_entry_id' => $this->taxTotal > 0 ? $taxEntry->id : null,
                'amount' => $this->purchasesTotal - $this->taxTotal,
            ],
            [
                'transaction_id' => $invoice->id,
                'account_id' => self::ACCOUNT_LOOKUP[$this->request->payment_method],
                'entry_type' => 'credit',
                'tax_ledger_entry_id' => null,
                'amount' => $this->purchasesTotal,
            ]
        ]);
    }

    private function returnErrorWithMessage(string $message): RedirectResponse
    {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'message' => $message
            ]);
    }
}
