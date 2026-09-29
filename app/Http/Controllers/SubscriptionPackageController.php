<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
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
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    $btnEdit = '<a href="' . $editUrl .  '"
                class="btn btn-sm btn-warning">Edit</a>';
                    $btnDelete = '<form action="' . $deleteUrl . '"method="POST"
                    class="d-inline">' . $csrf . $method . '<button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm(\'Apakah Anda Yakin Ingin Menghapus Paket Langganan Ini?\')">Hapus</button>
                </form>';

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
        return redirect()->route('admin.subscription-packages.index')->with('sycces', 'Paket langganan telah berhasil di hapus');
    }
}
