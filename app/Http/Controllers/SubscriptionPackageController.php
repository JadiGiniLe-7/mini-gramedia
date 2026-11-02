<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use App\Models\SubscriptionPackageUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Matrix\Functions;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = SubscriptionPackage::query();

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $editUrl = route('admin.subscription-packages.edit', $data->id);
                    $deleteUrl = route('admin.subscription-packages.destroy', $data->id);
                    $books = route('admin.subscription-packages.books', $data->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    $btnEdit = '<a href="' . $editUrl .  '"
                class="btn btn-sm btn-warning">Edit</a>';
                    $btnDelete = '<form action="' . $deleteUrl . '"method="POST"
                    class="d-inline">' . $csrf . $method . '<button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm(\'Apakah Anda Yakin Ingin Menghapus Paket Langganan Ini?\')">Hapus</button>
                </form>

                <a href="' . $books . '" class="btn btn-sm btn-info">
                    Buku
                </a>
                ';

                    return $btnEdit . $btnDelete;
                })->rawColumns(['action'])->toJson();
        }
        return view('admin.subscription-packages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscription-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'color'       => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
        ]);

        SubscriptionPackage::create($validateData);
        return redirect()->route('admin.subscription-packages.index')->with('success', 'Paket Langganan berhasil ditambahkan.');
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
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);
        return view('admin.subscription-packages.edit', compact('subscriptionPackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        $validateData = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'color'       => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
        ]);

        $subscriptionPackage->update($validateData);
        return redirect()->route('admin.subscription-packages.index')->with('success', 'Paket Langganan telah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        $subscriptionPackage->delete();
        return redirect()->route('admin.subscription-packages.index')->with('success', 'Paket langganan telah berhasil di hapus');
    }

    public function checkoutPage($id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);
        return view('user.subscription-packages.checkout', compact('subscriptionPackage'));
    }

    public function checkout(Request $request, $id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        SubscriptionPackageUser::create([
            'subscription_package_id' => $subscriptionPackage->id,
            'user_id' => Auth::id(), // ambil id org yg login, mendeteksi siapa user yg belinya
            // 30 hari dari sekarang, format tahun-bulan-tanggal
            'expired_date' => now()->addDays(30)->format('Y-m-d'),
        ]);
        return redirect()->route('home')->with('success', 'Pembayaran berhasil. Terima kasih telah berlangganan!');
    }
}
