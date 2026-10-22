@extends('layout.app')

@section('content')
    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

            <div>
                @if ($kategori)
                    <h2 class="mb-0">Kategori : {{ $kategori->name }}</h2>
                    @if($query)
                        <p class="text-muted mb-0 mt-1">Hasil Pencarian: "{{ $query }}"</p>
                    @endif
                @else
                    <h2 class="mb-0">Hasil Pencarian: "{{ $query }}"</h2>
                @endif
            </div>

            @if ($kategori)
                <div style="min-width: 300px; max-width: 450px; width: 100%;">
                    <form action="{{ route('search') }}" method="GET" class="input-group">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="text" name="q" class="form-control"
                               placeholder="Cari judul/penulis di kategori ini..."
                               value="{{ request('q') }}">

                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
            @endif

        </div>

        @if ($books->isEmpty())
            <p>Tidak ada hasil yang ditemukan.</p>
        @else

            <div class="row mt-3">
                @foreach ($books as $book)
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <img class="d-block mx-auto w-75 h-50" alt="" src="{{ asset($book->cover) }}" />
                                <div class="d-flex gap-2 mt-2">
                                    <p class="badge"><i class="fa-solid fa-mobile"></i>PDF</p>
                                    <p class="badge">{{ $book->page_of_book }}+</p>
                                </div>
                                <div>
                                    <h5><span style="font-size: 0.8rem;" class="text-secondary">{{ $book->writer }}</span>
                                    </h5>
                                    <br>
                                    <span style="font-size: 1rem;">{{ $book->title }}</span></h5>
                                    <h4 style="font-size: 1.2rem; font-weight: bold;">
                                        Rp {{ number_format($book->price, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
