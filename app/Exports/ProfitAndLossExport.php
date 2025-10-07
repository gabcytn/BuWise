<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitAndLossExport implements FromArray, WithHeadings, WithStyles
{
    private array $final_array;
    private float $revenue_sum = 0;
    private float $expense_sum = 0;

    public function __construct(
        private array $data
    ) {
        $this->iterate('revenues');
        $this->iterate('expenses');

        $this->final_array[] = [
            'Net Profit/Loss',
            $this->revenue_sum - $this->expense_sum,
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

        if ($key === 'revenues')
            $this->revenue_sum = (float) $sum;
        elseif ($key === 'expenses')
            $this->expense_sum = (float) $sum;

        $this->insertSeparator();
    }

    private function getAmount(string $key, string $debit, string $credit)
    {
        if ($key === 'expenses')
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
            $worksheet
                ->getStyle("A{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);

            // Apply right alignment to column B
            $worksheet
                ->getStyle("B{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $worksheet
                ->getStyle("B{$row}")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

            $cellValue = $worksheet->getCell("A{$row}")->getValue();  // Check column A (first column)
            // Check if the cell starts with "TOTAL"
            if (is_string($cellValue) && str_starts_with($cellValue, 'Total')) {
                $styles[$row]['font'] = ['bold' => true];
            }
        }

        $finalValue = $worksheet->getCell("B{$highestRow}")->getCalculatedValue();
        if (is_numeric($finalValue) && $finalValue < 0) {
            $worksheet
                ->getStyle("A{$highestRow}:B{$highestRow}")
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(Color::COLOR_RED);
        }

        return $styles;
    }
}
