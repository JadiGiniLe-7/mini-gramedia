@extends('layout.app')

@section('content')
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success my-3">{{ session('success') }}</div>
        @endif
        <div class="d-flex justify-content-between">
            <h3>Data Buku</h3>
            <a href="{{ route('admin.books.create') }}" class="btn btn-success">Tambah Data Buku</a>
        </div>
        <table class="table table-bordered mt-3" id="data-buku">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sampul Buku</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    {{-- Modal Detail --}}
    <div class="modal modal-blur fade" id="modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <img src="" id="data-cover" width="100%" />
                        </div>
                        <div class="col-4">
                            <div style="height: 80px">
                                <h3 class="text-primary" id="data-title"></h3>
                                <p class="badge badge-primary" id="data-category"></p>
                            </div>
                            <div>
                                <p>Harga <br> <span id="data-price" class="text-success"></span></p>
                                <p>Penerbit <br> <span id="data-publisher"></span></p>
                                <p>Tanggal Rilis <br> <span id="data-release-date"></span></p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="height: 80px"></div>
                            <div>
                                <p>Penulis <br> <span id="data-writer"></span></p>
                                <p>Bahasa <br> <span id="data-language"></span></p>
                                <p>Jumlah Halaman <br> <span id="data-page-of-book"></span> halaman</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#data-buku").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.books.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'coverImg',
                        name: 'coverImg',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    // data : dari nama field/addColumn/editColumn
                    // name : bookCategory.name : untuk search/sort carinya dari relasi
                    {
                        data: 'book_category_id',
                        name: 'bookCategory.name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }
                ],
            })

            // proses memunculkan modal ketika btn detail di klik
            $("#data-buku").on('click', '#btn-detail', function() {
                const btn = $(this);
                // ambil data attribut data-title="" yg dikirim di datatable controller
                let title = btn.data('title');
                let category = btn.data('category');
                let price =  "Rp " + btn.data('price');
                let publisher = btn.data('publisher');
                let writer = btn.data('writer');
                let pageOfBook = btn.data('pageOfBook');
                let releaseDate = btn.data('releaseDate');
                let language = btn.data('language');

                // simpan data ke span yg dikosongkan di modal
                $("#data-title").text(title);
                $("#data-category").text(category);
                $("#data-price").text(price);
                $("#data-publisher").text(publisher);
                $("#data-writer").text(writer);
                $("#data-page-of-book").text(pageOfBook);
                $("#data-release-date").text(releaseDate);
                $("#data-language").text(language);

                // isi src gambar di modal dengan data cover yg dikirim di datatable controller
                let cover = btn.data('cover');
                $("#data-cover").attr("src", cover);

                // panggil modal, munculkan
                $("#modal-detail").modal("show");
            });
        });
    </script>
@endpush
