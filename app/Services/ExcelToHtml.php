<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExcelToHtml
{
    public static function convert(string $path): string
    {
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();

        // Ambil batas data mentah
        $highestRow    = $sheet->getHighestDataRow();
        $highestColumn = Coordinate::columnIndexFromString(
            $sheet->getHighestDataColumn()
        );

        // Simpan semua nilai dulu, sambil tandai kolom mana yang benar-benar punya isi
        $rowsData       = [];
        $columnHasData  = [];
        $rowHasData     = [];

        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumn; $col++) {
                $columnLetter = Coordinate::stringFromColumnIndex($col);
                $cellAddress  = $columnLetter . $row;

                $cell  = $sheet->getCell($cellAddress);
                $value = trim((string) $cell->getFormattedValue());

                $rowsData[$row][$col] = $value;

                if ($value !== '') {
                    $columnHasData[$col] = true;
                    $rowHasData[$row]    = true;
                }
            }
        }

        // Kalau tidak ada data sama sekali
        if (empty($columnHasData)) {
            return '';
        }

        // Cari kolom pertama dan terakhir yang punya data
        $firstCol = min(array_keys($columnHasData));
        $lastCol  = max(array_keys($columnHasData));

        $htmlRows = '';
        $isHeader = true; // baris pertama yang berisi data jadi header

        for ($row = 1; $row <= $highestRow; $row++) {
            // skip baris kosong total
            if (empty($rowHasData[$row])) {
                continue;
            }

            $htmlRows .= '<tr>';

            for ($col = $firstCol; $col <= $lastCol; $col++) {
                $cellValue = $rowsData[$row][$col] ?? '';
                $escaped   = htmlspecialchars($cellValue, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                if ($isHeader) {
                    $htmlRows .= '<th>' . $escaped . '</th>';
                } else {
                    $htmlRows .= '<td>' . $escaped . '</td>';
                }
            }

            $htmlRows .= '</tr>';
            $isHeader = false;
        }

        if ($htmlRows === '') {
            return '';
        }

        return '<table class="matrix-table">' . $htmlRows . '</table>';
    }
}
