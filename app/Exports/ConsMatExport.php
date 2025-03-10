<?php

namespace App\Exports;

use App\Models\Transaction\ConsMat;
use App\Models\Transaction\ScopeStandart;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConsMatExport implements FromQuery, WithMapping, WithColumnWidths, WithStyles, WithDrawings, WithCustomStartCell, WithTitle
{
    protected int $index = 0;
    public function __construct(
        protected string $title
    ) {}

    public function query()
    {
        return ConsMat::query()->with('globalUnit');
    }

    public function title(): string
    {
        return $this->title;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Perusahaan');
        $drawing->setDescription('Ini adalah logo perusahaan.');
        $drawing->setPath(public_path('logo.png')); // Path gambar di folder public
        $drawing->setHeight(30); // Tinggi gambar dalam pixel
        $drawing->setWidth(150);
        $drawing->setCoordinates('A1'); // Menentukan sel tempat gambar dimulai
        $drawing->setOffsetX(10); // Jarak dari kiri
        $drawing->setOffsetY(10); // Jarak dari atas

        return $drawing;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 70,
            'C' => 15,
            'D' => 15,
        ];
    }

    public function startCell(): string
    {
        return 'A7'; // Data dimulai di bawah header
    }

    public function map($row): array
    {
        return [
            ++$this->index,
            $row->name,
            $row->qty,
            $row->globalUnit->name ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Ambil baris terakhir (biar tahu seberapa panjang data)
        $lastRow = $sheet->getHighestRow();

        // Menulis header langsung ke dalam Excel
        $sheet->setCellValue('B1', 'PT. PLN INDONESIA POWER');
        $sheet->setCellValue('B2', 'SUMMARY SCOPE STANDARD PEMELIHARAAN PERIODIK');
        $sheet->setCellValue('B3', 'MAJOR INSPECTION');
        $sheet->setCellValue('B4', 'GAS TURBINE (M70 F)');
        // detail
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'URAIAN');
        $sheet->setCellValue('C5', 'VOLUME');
        $sheet->setCellValue('D5', 'SATUAN');


        $sheet->mergeCells('A1:A4');
        $sheet->mergeCells('B1:D1');
        $sheet->mergeCells('B2:D2');
        $sheet->mergeCells('B3:D3');
        $sheet->mergeCells('B4:D4');
        $sheet->mergeCells('A5:A6');
        $sheet->mergeCells('B5:B6');
        $sheet->mergeCells('C5:C6');
        $sheet->mergeCells('D5:D6');

        // bold title
        $sheet->getStyle('A1:D6')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // background
        $sheet->getStyle('B1:D1')->applyFromArray([
            'fill' => array(
                'fillType' => Fill::FILL_SOLID, // Gunakan FILL_SOLID agar warna tampil dengan jelas
                'startColor' => [
                    'rgb' => 'A1E3F9' // Warna merah
                ]
            )
        ]);

        $sheet->getStyle('A5:D6')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID, // Gunakan FILL_SOLID agar warna tampil dengan jelas
                'startColor' => [
                    'rgb' => 'A1E3F9' // Warna merah
                ]
            )
        ]);

        // BORDER
        $sheet->getStyle("A1:D$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Hitam
                ],
            ],
            'alignment' => [
                'wrapText' => true,
            ],
        ]);
    }

    public function autoMerged(Worksheet $sheet, $lastRow, $start, $end)
    {
        $mergeRanges = [];

        // Looping dari baris 6 ke bawah untuk cek cell kosong
        for ($row = 7; $row <= $lastRow; $row++) {
            foreach (range($start, $end) as $column) {
                if (empty($sheet->getCell("{$column}{$row}")->getValue())) {
                    $mergeRanges[] = "{$column}" . ($row - 1) . ":{$column}{$row}";
                }
            }
        }

        // Merge dalam satu proses agar lebih cepat
        foreach ($mergeRanges as $range) {
            $sheet->mergeCells($range);
        }
    }
}
