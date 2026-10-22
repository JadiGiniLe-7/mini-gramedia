@extends('layout.app')

@section('content')
    @php $warna = $subscriptionPackage->color ?? '#6c63ff'; @endphp

    {{-- Banner Paket (warna mengikuti warna paket, jadi pakai inline style) --}}
    <div class="d-flex align-items-center justify-content-center text-center py-5 px-3"
        style="background: color-mix(in srgb, {{ $warna }} 14%, #ffffff); min-height: 220px;">
        <div>
            <h1 class="display-4 fw-bolder text-uppercase mb-2" style="color: {{ $warna }};">
                {{ $subscriptionPackage->name }}
            </h1>
            <p class="fs-2 fw-semibold text-secondary mx-auto mb-0">{{ $subscriptionPackage->description }}</p>
        </div>
    </div>

    <div class="container py-4">
        {{-- Nama, harga, tombol langganan --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">
            <h2 class="m-0 text-dark">{{ $subscriptionPackage->name }}</h2>
            <div class="d-flex align-items-center gap-3">
                <h3 class="m-0 text-dark">Rp{{ number_format($subscriptionPackage->price, 0, ',', '.') }}</h3>
                <a href="#" class="btn btn-primary rounded-pill px-4">Langganan Sekarang</a>
            </div>
        </div>

        {{-- Search bar --}}
        <div class="d-flex justify-content-end mt-4 mb-4">
            <form action="{{ route('subscription-packages.show', $subscriptionPackage->id) }}" method="GET"
                class="input-icon w-100" style="max-width: 540px;">
                <span class="input-icon-addon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="q" value="{{ $query }}"
                    class="form-control form-control-lg form-control-rounded" placeholder="Cari dalam paket ini" />
            </form>
        </div>

        @if ($query)
            <p class="text-muted">Hasil pencarian: "{{ $query }}"
                <a href="{{ route('subscription-packages.show', $subscriptionPackage->id) }}"
                    class="ms-2">Reset</a>
            </p>
        @endif

        {{-- Daftar buku --}}
        @if ($packageBooks->isEmpty())
            <div class="text-center text-secondary py-5">
                @if ($query)
                    Tidak ada buku dengan judul "{{ $query }}" pada paket ini.
                @else
                    Belum ada buku yang tertaut pada paket langganan ini.
                @endif
            </div>
        @else
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
                @foreach ($packageBooks as $packageBook)
                    @php $book = $packageBook->book; @endphp
                    <div class="col">
                        <div class="card h-100 rounded-4">
                            <div class="card-body">
                                <img class="d-block mx-auto w-100 object-fit-contain" style="height: 190px;"
                                    alt="Sampul {{ $book->title }}" src="{{ asset($book->cover) }}" />
                                <div class="d-flex gap-2 mt-3">
                                    <span class="badge bg-secondary-lt"><i class="fa-solid fa-mobile me-1"></i>PDF</span>
                                    <span class="badge bg-secondary-lt">{{ $book->page_of_book }}+</span>
                                </div>
                                <div class="mt-2">
                                    <div class="small text-secondary">{{ $book->writer }}</div>
                                    <div>{{ $book->title }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
