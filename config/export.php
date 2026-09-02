<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Cada sección de $sections es:
 * ['title' => string, 'headers' => string[], 'rows' => array[]]
 */
function exportToExcel(array $sections, string $filename)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $row = 1;
    $maxCols = 1;

    foreach ($sections as $section) {

        $maxCols = max($maxCols, count($section['headers']));

        $sheet->setCellValue('A' . $row, $section['title']);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
        $row++;

        $headerRow = $row;

        foreach ($section['headers'] as $col => $header) {
            $sheet->setCellValue(chr(65 + $col) . $headerRow, $header);
        }

        $lastCol = chr(64 + count($section['headers']));
        $sheet->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)
            ->getFont()->setBold(true);

        $row++;

        foreach ($section['rows'] as $record) {
            $col = 0;
            foreach ($record as $value) {
                $sheet->setCellValue(chr(65 + $col) . $row, $value);
                $col++;
            }
            $row++;
        }

        $row += 2;
    }

    foreach (range(0, $maxCols - 1) as $col) {
        $sheet->getColumnDimension(chr(65 + $col))->setAutoSize(true);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

/**
 * Cada sección de $sections es:
 * ['title' => string, 'headers' => string[], 'rows' => array[]]
 */
function exportToPdf(array $sections, string $title, string $filename)
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'sans-serif');

    $dompdf = new Dompdf($options);

    $html = '
        <style>
            body { font-family: sans-serif; font-size: 12px; }
            h2 { color: #14532d; margin-bottom: 15px; }
            h3 { color: #14532d; margin-top: 25px; margin-bottom: 10px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
            th { background: #14532d; color: white; padding: 8px; text-align: left; }
            td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
            tr:nth-child(even) { background: #f9f9f9; }
        </style>
    ';

    $html .= '<h2>' . htmlspecialchars($title) . '</h2>';

    foreach ($sections as $section) {

        $html .= '<h3>' . htmlspecialchars($section['title']) . '</h3>';
        $html .= '<table><thead><tr>';

        foreach ($section['headers'] as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }

        $html .= '</tr></thead><tbody>';

        foreach ($section['rows'] as $record) {
            $html .= '<tr>';
            foreach ($record as $value) {
                $html .= '<td>' . htmlspecialchars($value) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
    }

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
    exit;
}
