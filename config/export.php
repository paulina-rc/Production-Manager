<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

function exportToExcel(array $data, array $headers, string $filename)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    foreach ($headers as $col => $header) {
        $sheet->setCellValue(chr(65 + $col) . '1', $header);
    }

    $style = $sheet->getStyle('A1:' . chr(64 + count($headers)) . '1');
    $style->getFont()->setBold(true);

    foreach ($data as $row => $record) {
        $col = 0;
        foreach ($record as $value) {
            $sheet->setCellValue(chr(65 + $col) . ($row + 2), $value);
            $col++;
        }
    }

    foreach (range(0, count($headers) - 1) as $col) {
        $sheet->getColumnDimension(chr(65 + $col))->setAutoSize(true);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

function exportToPdf(array $data, array $headers, string $title, string $filename)
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'sans-serif');

    $dompdf = new Dompdf($options);

    $html = '
        <style>
            body { font-family: sans-serif; font-size: 12px; }
            h2 { color: #14532d; margin-bottom: 15px; }
            table { width: 100%; border-collapse: collapse; }
            th { background: #14532d; color: white; padding: 8px; text-align: left; }
            td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
            tr:nth-child(even) { background: #f9f9f9; }
        </style>
    ';

    $html .= '<h2>' . htmlspecialchars($title) . '</h2>';
    $html .= '<table><thead><tr>';

    foreach ($headers as $header) {
        $html .= '<th>' . htmlspecialchars($header) . '</th>';
    }

    $html .= '</tr></thead><tbody>';

    foreach ($data as $record) {
        $html .= '<tr>';
        foreach ($record as $value) {
            $html .= '<td>' . htmlspecialchars($value) . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
    exit;
}
