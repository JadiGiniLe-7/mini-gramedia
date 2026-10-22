<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionPackageBook;
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
                ->addColumn('checkAdded', function ($row) use ($subscriptionPackage) {
                    // pluck : ambil semua dari book_id dari subscriptionPackageBook, lalu ubah menjadi array
                    $subscriptionPackageBook = $subscriptionPackage->subscriptionPackageBooks->pluck('book_id')->toArray();
                    // cek apakah book_id dari row ada dalam array subscriptionPackageBook, jika ada maka buku sudah tertaut dengan paket langganan ini (checkbox dihilangkan)
                    return in_array($row->id, $subscriptionPackageBook) ? 'Ya' : 'Tidak';
                })

                ->rawColumns(['coverImg'])
                ->toJson();
        }
        return view('admin.subscription-packages.books', compact('subscriptionPackage'));
    }

    public function store(Request $request, $id)
    {
        $bookIds = $request->book_ids;
        foreach ($bookIds as $bookId) {
            SubscriptionPackageBook::create([
                'subscription_package_id' => $id,
                'book_id' => $bookId,
            ]);
        }
        return redirect()->back()->with('success', 'Buku berhasil ditambahkan ke paket langganan');
    }
}
