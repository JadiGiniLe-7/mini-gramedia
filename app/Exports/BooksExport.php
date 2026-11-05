<?php

namespace App\Exports;

use App\Models\Book;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class BooksExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents
{
    private int $rowNumber = 0;

    public function collection(): Collection
    {
        return Book::query()
            ->with('bookCategory')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Alamat Gambar Sampul Buku',
            'Nama Kategori Buku',
            'Judul Buku',
            'Penulis',
            'Penerbit',
            'Tanggal Rilis',
            'Jumlah Halaman',
            'Bahasa',
            'Harga',
        ];
    }

    public function map($book): array
    {
        return [
            ++$this->rowNumber,
            asset($book->cover),
            $book->bookCategory->name,
            $book->title,
            $book->writer,
            $book->publisher,
            date('d-m-Y', strtotime($book->release_date)),
            $book->page_of_book,
            $book->language,
            $book->price,
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function columnFormats(): array
    {
        return [
            'J' => '"Rp" #,##0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', 'Data Buku');
                $sheet->getStyle('A1:J1')->getFont()->setBold(true)->setSize(16);

                $sheet->getStyle('A4:J4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '404040'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);

                $sheet->getStyle("A4:J{$lastRow}")->applyFromArray([
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
