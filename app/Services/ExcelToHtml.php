<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelToHtml
{
    public static function convert(string $path): string
    {
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestDataRow();

        // kolom yg ditampilin
        $columns = [
            'A' => 'NO',
            'B' => 'KODE UNIT',
            'C' => 'KUK KURIKULUM',
            'F' => 'BOBOT (JAM)',
        ];

        // header
        $html  = '<table class="matrix-table"><thead><tr>';
        foreach ($columns as $label) {
            $html .= '<th>' . htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        $totalHours = 0;

        for ($row = 1; $row <= $highestRow; $row++) {
            $noRaw   = $sheet->getCell('A' . $row)->getCalculatedValue();
            $kodeRaw = trim((string) $sheet->getCell('B' . $row)->getFormattedValue());

            if (! is_numeric($noRaw) || $kodeRaw === '') {
                continue;
            }

            $html .= '<tr>';

            foreach (array_keys($columns) as $colLetter) {
                $value   = trim((string) $sheet->getCell($colLetter . $row)->getFormattedValue());
                $escaped = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $html   .= '<td>' . $escaped . '</td>';

                // kalo ini kolom F (BOBOT JAM) dan berupa angka, tambahin ke total
                if ($colLetter === 'F') {
                    $hours = $sheet->getCell($colLetter . $row)->getCalculatedValue();
                    if (is_numeric($hours)) {
                        $totalHours += (float) $hours;
                    }
                }
            }

            $html .= '</tr>';
        }

        // Tambahin baris total di bawah data terakhir
        $html .= '<tr class="matrix-total-row">';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td><strong>Jumlah Jam Pelajaran</strong></td>';
        $html .= '<td><strong>' . (int) $totalHours . '</strong></td>';
        $html .= '</tr>';

        $html .= '</tbody></table>';

        return $html;
    }
}
