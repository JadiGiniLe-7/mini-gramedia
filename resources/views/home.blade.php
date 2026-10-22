@extends('layout.app')

@push('styles')
    <style>
        .slick-prev:before,
        .slick-next:before {
            color: #333
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        @if (Session::get('success'))
            <div class="alert alert-important alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                    </div>
                    <div>{{ Session::get('success') }}</div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <div>{{ Session::get('error') }}</div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif
        {{-- Carousel Section --}}
        <div id="carousel-sample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="2"></button>
            </div>
            {{-- BENNER --}}
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" alt="Slide 1"
                        src="https://i.pinimg.com/1200x/73/50/21/735021466854bdb9e12de64f7e2cc31a.jpg" />
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" alt="Slide 2"
                        src="https://i.pinimg.com/1200x/4a/da/6b/4ada6baba6477cc995daffa5809b7bcb.jpg" />
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" alt="Slide 3"
                        src="https://i.pinimg.com/1200x/c3/bd/77/c3bd77bb053a1d50a3dbcd92e5eb54c7.jpg" />
                </div>
            </div>
            <a class="carousel-control-prev" data-bs-target="#carousel-sample" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" data-bs-target="#carousel-sample" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>

        {{-- Paket Langganan Section --}}
        <div class="mt-4">
            <div class="mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-yellow text-yellow-fg p-2"><i class="fa-solid fa-crown fs-3"></i></span>
                    <h2 class="m-0 text-dark">Paket Langganan</h2>
                </div>
            </div>

            <div class="row g-4">
                @foreach ($subscriptionPackages as $Package)
                    <div class="col-md-4">
                        <div class="card h-100"
                            style="background: linear-gradient(135deg, #ffffff 0%, {{ $Package->color ?? '#000000' }} 100%);">
                            <div class="card-body row align-items-center">
                                <div class="col-4"></div>
                                <div class="col-8 text-center text-dark">
                                    <h2 class="fw-bold m-0 text-uppercase">{{ $Package->name }}</h2>
                                    <p class="text-secondary fw-bold m-0">PACKAGE</p>
                                    <div>
                                        Rp<span
                                            class="fs-1 fw-bold text-warning">{{ number_format($Package->price, 0, ',', '.') }}</span>
                                        <div>
                                            <span class="text-secondary fw-bold">30 DAYS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- Buku Baru Dirilis Section --}}
        <div class="mt-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-blue text-blue-fg p-2"><i class="fa-solid fa-book fs-3"></i></span>
                <h2 class="m-0 text-dark">Buku Baru Dirilis</h2>
            </div>
            {{-- DAFTAR BUKU BARiS --}}
            <div id="wrapper-slider">
                @forelse ($latestBooks as $book)
                    <div class="col-md-3 px-2">
                        <div class="card">
                            <div class="card-body">
                                <img class="d-block mx-auto w-75 h-50" alt="Book Cover"
                                    src="{{ asset($book->cover) }}" />
                                <div class="d-flex gap-2 mb-2">
                                    <span class="badge bg-blue-lt"><i class="fa-solid fa-mobile me-1"></i>PDF</span>
                                    <span class="badge bg-secondary-lt">3+</span>
                                </div>
                                <div>
                                    <h5><span style="font-size: 0.8rem" class="text-secondary">{{ $book->author }}</span>
                                        <br><span style="font-size: 1rem">{{ $book->title }}</span>
                                    </h5>
                                    <h4 style="font-size: 1.2rem; font-weight:bold">Rp
                                        {{ number_format($book->price, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Tidak ada buku terbaru yang tersedia.</p>
                @endforelse
            </div>
        </div>
        {{-- Buku Gratis Section --}}
        <div class="mt-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <span class="badge bg-blue text-blue-fg p-2"><i class="fa-solid fa-book fs-3"></i></span>
                <h2 class="mt-0 text-dark">Buku Gratis</h2>
            </div>
            {{-- Daftar Buku --}}
            <div class="row">
                @forelse ($freeBooks as $book)
                    <div class="col-4">
                        <div class="card d-flex flex-column">
                            <div class="row row-0 flex-fill">
                                <div class="col-md-3">
                                    <a href="#">
                                        <img src="{{ asset($book->cover) }}" class="w-100 h-100 object-cover"
                                            alt="Card side image" />
                                    </a>
                                </div>
                                <div class="col">
                                    <div class="card-body h-full d-flex flex-column">
                                        <h3 class="card-title">
                                            <div class="badge"><i class="fa-solid fa-mobile"></i>PDF</div>
                                        </h3>
                                        <div class="text-secondary">
                                            Penulis
                                            <br><span class="text-dark">{{ $book->writer }}</span>
                                        </div>
                                        <div class="d-flex align-items-center pt-4 mt-auto">
                                            <h3>
                                                <span class="text-dark">Rp 0</span>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>

        </div>
        <div class="row mt-2">
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {


            $('#wrapper-slider').slick({
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });
        })
    </script>
@endpush
