<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('admin.books.edit', $row->id) . '" class="btn btn-primary me-2">Edit</a>';
                    $btnDelete = '<form method="POST" action="' . route(
                        'admin.books.destroy',
                        $row->id
                    ) . '">' . csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger">Delete</button></form>';
                    $btnDetail = '<button type="button" class="btn btn-primary" id="btn-detail"
                        data-cover="' . asset($row->cover) . '"
                        data-title="' . $row->title . '"
                        data-category="' . $row->bookCategory->name . '"
                        data-price="' . $row->price . '"
                        data-writer="' . $row->writer . '"
                        data-publisher="' . $row->publisher . '"
                        data-language="' . $row->language . '"
                        data-page-of-book="' . $row->page_of_book . '"
                        data-release-date="' . date('d M Y', strtotime($row->release_date)) . '"
                    >Detail</button>';

                    return $btnEdit . $btnDelete . $btnDetail;
                })
                ->rawColumns(['action', 'coverImg'])
                ->toJson();
        }
        return view('admin.books.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookCategories = BookCategory::all();
        return view('admin.books.create', compact('bookCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // mimes : opsi jenis file yang diupload
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png,svg,webp'],
            'title' => ['required'],
            'writer' => ['required'],
            'publisher' => ['required'],
            'price' => ['required', 'numeric'],
            'language' => ['required'],
            'description' => ['nullable'],
            // exists:table,field = data harus uda ada di table book_categories field id
            'book_category_id' => ['required', 'exists:book_categories,id'],
            'page_of_book' => ['required', 'numeric'],
            'release_date' => ['required', 'date']
        ]);

        // kalau input file cover ada file nya maka proses simpen file ke storage
        if ($request->file('cover')) {
            $cover = $request->file('cover');
            // bikin nama file dari waktu gambar diupload disambungkan "." ekstensi file : 123456.jpg (contoh hasil nama file)
            $namaFile = time() . "." . $cover->getClientOriginalExtension();
            // disk : penyimpan storage (nanti diganti ke layanan cloud setelah dihosting)
            Storage::disk('public')->putFileAs('covers', $cover, $namaFile);
            // ambil alamat gambar untuk disimpan di database, timpa data cover di validasi dengan alamat gambar yg udah di upload
            $validatedData['cover'] = Storage::url('covers/' . $namaFile);
        }

        // simpan data ke model Book, data yg disimpan data2 dari hasil validasi
        Book::create($validatedData);
        return redirect()->route('admin.books.index')->with('success', 'Berhasil membuat data buku baru!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
