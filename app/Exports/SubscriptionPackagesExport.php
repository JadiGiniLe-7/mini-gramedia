<?php

namespace App\Exports;

use App\Models\SubscriptionPackage;
use App\Models\SubscriptionPackageBook;
use App\Models\SubscriptionPackageUser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class SubscriptionPackagesExport implements
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
        $today = today()->toDateString();

        return SubscriptionPackage::query()
            ->select('subscription_packages.*')
            ->addSelect([
                'books_count' => SubscriptionPackageBook::query()
                    ->selectRaw('COUNT(DISTINCT book_id)')
                    ->whereColumn('subscription_package_books.subscription_package_id', 'subscription_packages.id'),
                'active_customers_count' => SubscriptionPackageUser::query()
                    ->selectRaw('COUNT(DISTINCT user_id)')
                    ->whereColumn('subscription_package_users.subscription_package_id', 'subscription_packages.id')
                    ->whereDate('expired_date', '>=', $today),
            ])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Nama Paket Langganan',
            'Harga',
            'Jumlah Buku',
            'Pelanggan Aktif',
        ];
    }

    public function map($subscriptionPackage): array
    {
        return [
            ++$this->rowNumber,
            $subscriptionPackage->name,
            $subscriptionPackage->price,
            $subscriptionPackage->books_count,
            $subscriptionPackage->active_customers_count,
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function columnFormats(): array
    {
        return [
            'C' => '"Rp" #,##0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'Data Paket Langganan dengan Buku dan Pengguna');
                $sheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(16);

                $sheet->getStyle('A4:E4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '404040'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);

                $sheet->getStyle("A4:E{$lastRow}")->applyFromArray([
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
