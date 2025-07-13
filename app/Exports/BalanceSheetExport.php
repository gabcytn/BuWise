<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BalanceSheetExport implements FromArray, WithHeadings, WithStyles
{
    private array $final_array;
    private float $liabilities_sum = 0;
    private float $equities_sum = 0;

    public function __construct(
        private array $data
    ) {
        $this->prepareData();
    }

    private function prepareData()
    {
        $this->iterate('assets');
        $this->iterate('liabilities');
        $this->iterate('equities');

        $this->final_array[] = [
            'Total Liabilities and Equities',
            $this->liabilities_sum + $this->equities_sum,
        ];
    }

    private function iterate(string $key)
    {
        $this->insertSeparator(ucfirst($key), '');
        $sum = 0;
        $data = $this->data;
        foreach ($data[$key] as $item) {
            $amount = $this->getAmount($key, $item->debit, $item->credit);

            $this->final_array[] = [
                '    ' . $item->acc_name,
                $amount,
            ];

            $sum += $amount;
        }

        $this->final_array[] = [
            'Total ' . ucfirst($key),
            $sum,
        ];

        if ($key === 'liabilities')
            $this->liabilities_sum = $sum;
        elseif ($key === 'equities')
            $this->equities_sum = $sum;

        $this->insertSeparator();
    }

    private function getAmount(string $key, string $debit, string $credit)
    {
        if ($key === 'assets')
            return $debit - $credit;
        else
            return $credit - $debit;
    }

    private function insertSeparator(string $col1 = '', string $col2 = '')
    {
        $this->final_array[] = [
            $col1,
            $col2,
        ];
    }

    public function array(): array
    {
        return $this->final_array;
    }

    public function headings(): array
    {
        return [
            'Account',
            'Total'
        ];
    }

    public function styles(Worksheet $worksheet)
    {
        $styles = [
            1 => ['font' => ['bold' => true]]
        ];

        $highestRow = $worksheet->getHighestRow();

        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $worksheet->getCell("A{$row}")->getValue();  // Check column A (first column)

            // Initialize alignment for the row
            $styles[$row]['alignment'] = [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ];

            // Check if the cell starts with "IMPORTANT"
            if (is_string($cellValue) && str_starts_with($cellValue, 'Total')) {
                $styles[$row]['font'] = ['bold' => true];
            }
        }

        return $styles;
    }
}
