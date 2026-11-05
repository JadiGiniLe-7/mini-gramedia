@extends('layout.app')

@section('content')
    <div class="container py-5">
        <div class="row g-4">
            <aside class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body text-center py-4">
                        <span class="avatar avatar-xl bg-primary-lt mb-3">
                            <i class="fa-solid fa-user" aria-hidden="true"></i>
                        </span>
                        <h2 class="h3 mb-1">{{ $user->name }}</h2>
                        <div class="text-secondary">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title mb-0">Ringkasan Profil</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span><i class="fa-solid fa-book text-primary me-2" aria-hidden="true"></i>Koleksi Buku</span>
                            <span class="badge bg-blue-lt">{{ $checkoutCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-crown text-warning me-2" aria-hidden="true"></i>Langganan</span>
                            <span class="badge bg-yellow-lt">{{ $subscriptionCount }}</span>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="col-lg-8">
                <div class="mb-4">
                    <h1 class="h2 mb-1">Riwayat Pembelian</h1>
                    <p class="text-secondary mb-0">Riwayat paket langganan dan koleksi Anda.</p>
                </div>

                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <h2 class="card-title mb-0">Daftar Transaksi</h2>
                        <form method="GET" action="{{ route('purchase-history.index') }}" class="d-flex align-items-center gap-2">
                            <label for="type" class="form-label mb-0">Jenis</label>
                            <select id="type" name="type" class="form-select" onchange="this.form.submit()">
                                <option value="subscription" @selected($type === 'subscription')>Paket Langganan</option>
                                <option value="book" @selected($type === 'book')>Koleksi Buku</option>
                            </select>
                        </form>
                    </div>

                    @if ($type === 'subscription')
                        @if ($subscriptionPurchases->isEmpty())
                            <div class="card-body text-center text-secondary py-5">
                                Belum ada transaksi paket langganan.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Pembelian</th>
                                            <th>Nama Paket Langganan</th>
                                            <th>Masa Berlaku Hingga</th>
                                            <th class="text-end">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subscriptionPurchases as $purchase)
                                            <tr>
                                                <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $purchase->subscriptionPackage->name }}</td>
                                                <td>{{ $purchase->expired_date->format('d/m/Y') }}</td>
                                                <td class="text-end">
                                                    Rp {{ number_format($purchase->subscriptionPackage->price, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($subscriptionPurchases->hasPages())
                                <div class="card-body">
                                    {{ $subscriptionPurchases->links() }}
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="card-body text-center text-secondary py-5">
                            Riwayat transaksi koleksi buku belum tersedia.
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
@endsection
