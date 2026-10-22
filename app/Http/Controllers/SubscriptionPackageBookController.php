<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPackageBookController extends Controller
{
    public function show($subscriptionPackageId, Request $request)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($subscriptionPackageId);
        //
        if ($request->ajax()) {
            $model = Book::query();

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('coverImg', function ($row) {
                    // asset : mengambil file yang ada di folder public
                    return '<img src="' . asset($row->cover) . '" width="100" class="d-block mx-auto" />';
                })
                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })
                ->editColumn('book_category_id', function ($row) {
                    // bookCategory diambil dari func relasi yg ada di model Book
                    return $row->bookCategory->name;
                })
                ->rawColumns(['coverImg'])
                ->toJson();
        }
        return view('admin.subscription-packages.books', compact('subscriptionPackage'));
    }
}
