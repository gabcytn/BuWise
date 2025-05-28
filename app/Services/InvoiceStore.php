<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceStore
{
    public function __construct(
        private Request $request
    ) {}

    public function store()
    {
        $request = $this->request;
        try {
            DB::beginTransaction();

            $filename = (string) Str::uuid() . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $invoice = $this->creatInvoice($filename);
            $rowNumbers = $this->getRowNumbers($request);
            $items = $this->getInvoiceItems($request, $rowNumbers);
            $this->createInvoiceLines($items, $invoice);
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
        $invoice = Invoice::create([
            'client_id' => $request->client,
            'image' => $filename,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date ?? null,
            'transaction_type_id' => $request->transaction_type,
            'invoice_number' => $request->invoice_number,
            'supplier' => $request->supplier ?? null,
            'vendor' => $request->vendor ?? null,
            'payment_method' => $request->payment_method,
            'tax_id' => $request->tax !== '0' ? $request->tax : null,
            'discount_type' => $request->discount_type,
            'is_paid' => $request->invoice_status === 'paid'
        ]);
        return $invoice;
    }

    private function getInvoiceItems(Request $request, array $rowNumbers): array
    {
        $items = [];
        foreach ($rowNumbers as $rowNumber) {
            $item = [
                'item_name' => $request->input("item_$rowNumber"),
                'qty' => $request->input("qty_$rowNumber"),
                'unit_price' => $request->input("unit_price_$rowNumber"),
                'discount' => $request->input("discount_$rowNumber", '0'),
                'tax' => $request->input("tax_$rowNumber", '0'),
            ];

            $items[] = $item;
        }
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

    private function createInvoiceLines(array $items, Invoice $invoice)
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
}
