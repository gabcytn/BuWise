<?php

namespace App\Imports;

use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SalesImport implements ToCollection, WithCalculatedFormulas
{
    private string $client_id;
    private string $creator_id;
    private string $transaction_type;

    public function __construct(string $client_id, string $creator_id, string $transaction_type)
    {
        $this->client_id = $client_id;
        $this->creator_id = $creator_id;
        $this->transaction_type = $transaction_type;
    }

    public function collection(Collection $rows)
    {
        try {
            DB::beginTransaction();
            switch ($this->transaction_type) {
                case 'sales':
                    $this->handleSales($rows);
                    break;
                case 'purchases':
                    $this->handlePurchases($rows);
                    break;
                default:
                    break;
            }
            DB::commit();
            Log::info('Successfully migrated data');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    private function handleSales(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->salesEntry($row);
        }
    }

    private function handlePurchases(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->purchasesEntry($row);
        }
    }

    private function validateRow(Collection $row): Collection|null
    {
        $invoice_number = $row[0];
        $date_column = $row[1];
        $description = $row[2];
        $bill_to = $row[3];
        $amount = $row[4];
        $vat = $row[5];
        $total = $row[6];

        $is_valid = is_numeric($invoice_number) &&
            $date_column !== 'CANCELLED' &&
            $description !== 'CANCELLED' &&
            $bill_to !== 'CANCELLED' &&
            is_numeric($amount) &&
            is_numeric($vat) &&
            is_numeric($total);

        if ($is_valid) {
            $row[1] = Date::excelToDateTimeObject((int) $row[1])->format('Y-m-d');
            return $row;
        }

        return null;
    }

    private function salesEntry(Collection $row)
    {
        $row = $this->validateRow($row);
        if (!$row)
            return;
        $tr = $this->createTransaction($row);
        $tax_entry = LedgerEntry::create([
            'transaction_id' => $tr->id,
            'account_id' => LedgerAccount::OUTPUT_VAT_PAYABLE,
            'tax_id' => null,
            'tax_ledger_entry_id' => null,
            'entry_type' => 'credit',
            'description' => null,
            'amount' => $row[5],
        ]);
        LedgerEntry::insert([
            [
                'transaction_id' => $tr->id,
                'account_id' => LedgerAccount::SALES,
                'tax_id' => null,  // TODO: set to id of vat
                'tax_ledger_entry_id' => $tax_entry->id,
                'entry_type' => 'credit',
                'description' => null,
                'amount' => $row[4],
            ],
            [
                'transaction_id' => $tr->id,
                'account_id' => 5,
                'tax_id' => null,
                'tax_ledger_entry_id' => null,
                'entry_type' => 'debit',
                'description' => null,
                'amount' => $row[6],
            ]
        ]);
    }

    private function purchasesEntry(Collection $row)
    {
        $row = $this->validateRow($row);
        if (!$row)
            return;
        $tr = $this->createTransaction($row);
        $tax_entry = LedgerEntry::create([
            'transaction_id' => $tr->id,
            'account_id' => LedgerAccount::INPUT_VAT_RECEIVABLE,
            'entry_type' => 'debit',
            'amount' => $row[5],
        ]);
        LedgerEntry::insert([
            [
                'transaction_id' => $tr->id,
                'account_id' => LedgerAccount::GENERAL_EXPENSE,
                'tax_id' => null,  // TODO: set to id of vat
                'tax_ledger_entry_id' => $tax_entry->id,
                'entry_type' => 'debit',
                'description' => null,
                'amount' => $row[4],
            ],
            [
                'transaction_id' => $tr->id,
                'account_id' => LedgerAccount::ACCOUNTS_RECEIVABLE,
                'tax_id' => null,
                'tax_ledger_entry_id' => null,
                'entry_type' => 'credit',
                'description' => null,
                'amount' => $row[6],
            ]
        ]);
    }

    private function createTransaction(Collection $row): Transaction
    {
        return Transaction::create([
            'client_id' => $this->client_id,
            'created_by' => $this->creator_id,
            'status' => $row['1'] < now()->startOfYear()->format('Y-m-d') ? 'archived' : 'approved',
            'type' => 'journal',
            'kind' => $this->transaction_type,
            'amount' => $row[6],
            'date' => $row['1'],
            'payment_method' => null,
            'description' => $row[2],
            'reference_no' => $row[0],
        ]);
    }
}
