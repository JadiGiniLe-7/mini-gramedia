@extends('layout.app')

@section('content')
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success my-3">
                {{ session('success') }}
            </div>
        @endif
        <div class="justify-content-between">
            <h3>Data Buku yang akan Dipilih Untuk Paket Langganan
                <b>{{ $subscriptionPackage->name }}</b>
            </h3>

            <form action="{{ route('admin.subscription-packages.books.store', $subscriptionPackage->id) }}" id="addBookForm"
                method="POST">
                @csrf
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Daftar Buku</span>
                        <button type="submit" id="btnSubmitBooks" class="btn btn-primary d-none">
                            <i class="fas fa-plus me-1"></i>
                            Tambah ke paket langganan
                            (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mt-3 w-100" id="data-buku">
                            <thead>
                                <tr>
                                    {{-- untuk checkbox --}}
                                    <th></th>
                                    <th>#</th>
                                    <th>Sampul Buku</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#data-buku").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.subscription-packages.books', $subscriptionPackage->id) }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        searchable: false,
                        orderable: false,
                        // sama kaya editColumn di serverside
                        render: function(data, type, row) {
                            if (row['checkAdded'] === 'Ya') {
                                return ''; // jika buku sudah di tambahkan tidak menampilkan checkbox
                            }
                            // (data) isinya dari data: 'id'
                            // name pake [] karena yg diceklis bisa lebih dari 1 (array)
                            // tampilkan checkbox jika buku belum di tambahkan
                            return `<input type="checkbox" value="${row['id']}" name="book_ids[]"
                            class="book-checkbox">`;
                            // class book-checkbox ditambahkan untuk mendeteksi jumlah checkbox yg diceklis nantinya
                        }
                    },
                    {
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
                ],
            });

            // fungsi untuk mengecek status centang & toggle tombol
            function updateButtonVisibility() {
                let checkedCount = $('.book-checkbox:checked').length;
                $('#selectedCount').text(checkedCount);

                if (checkedCount > 0) {
                    $('#btnSubmitBooks').removeClass('d-none');
                } else {
                    $('#btnSubmitBooks').addClass('d-none');
                }
            }
            // event listener saat checkbox individu diubah
            $('#data-buku tbody').on('change', '.book-checkbox', function() {
                updateButtonVisibility();
            })

            $('#data-buku').on('draw.dt', function() {
                updateButtonVisibility();
            });
        });
    </script>
@endpush
