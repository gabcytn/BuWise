<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataExport implements FromArray, WithHeadings, WithStyles
{
    private array $export_data = [];

    public function __construct(
        private string $accountant_id
    ) {
        $this->prepareData();
    }

    private function prepareData()
    {
        $entries = DB::table('ledger_entries AS le')
            ->join('ledger_accounts AS accounts', 'accounts.id', '=', 'le.account_id')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->select(
                'tr.reference_no',
                'le.amount',
                'le.entry_type',
                'accounts.name AS account_name',
                'users.name AS client_name'
            )
            ->where('users.accountant_id', '=', $this->accountant_id)
            ->orderBy('tr.reference_no')
            ->get();

        $last_ref = null;

        foreach ($entries as $entry) {
            if ($last_ref !== null && $entry->reference_no !== $last_ref) {
                $this->export_data[] = ['', '', '', '', ''];
            }

            $this->export_data[] = [
                $entry->reference_no,
                $entry->amount,
                ucfirst($entry->entry_type),
                $entry->account_name,
                $entry->client_name,
            ];

            $last_ref = $entry->reference_no;
        }
    }

    public function array(): array
    {
        return $this->export_data;
    }

    public function headings(): array
    {
        return ['Reference No.', 'Amount', 'Entry Type', 'Account Name', 'Client Name'];
    }

    public function styles(Worksheet $worksheet)
    {
        $styles = [
            1 => ['font' => ['bold' => true]]
        ];

        // Apply left alignment to all rows
        $highestRow = $worksheet->getHighestRow();
        for ($row = 1; $row <= $highestRow; $row++) {
            $styles[$row]['alignment'] = ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT];
        }

        return $styles;
    }
}
