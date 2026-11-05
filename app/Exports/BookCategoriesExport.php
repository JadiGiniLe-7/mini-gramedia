<?php

namespace App\Exports;

use App\Models\BookCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class BookCategoriesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents
{
    private int $rowNumber = 0;p

    public function collection(): Collection
    {
        return BookCategory::query()
            ->withCount('books')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Nama Kategori',
            'Jumlah Buku',
        ];
    }

    public function map($bookCategory): array
    {
        return [
            ++$this->rowNumber,
            $bookCategory->name,
            $bookCategory->books_count,
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', 'Data Kategori Buku');
                $sheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(16);

                $sheet->getStyle('A4:C4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '404040'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);

                $sheet->getStyle("A4:C{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
