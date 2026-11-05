<?php

namespace App\Exports;

use App\Models\BookCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataExport implements FromCollection
{
    public function collection(): Collection
    {
        return BookCategory::all();
    }

    
}
