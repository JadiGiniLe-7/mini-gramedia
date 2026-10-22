@extends('layout.app')

@section('content')
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success my-3">{{ session('success') }}</div>
        @endif
        <div class="d-flex justify-content-between">
            <h3>Data Buku yang akan Dipilih Untuk Paket Langganan <b>{{ $subscriptionPackage->name }}</b></h3>
        </div>
        <table class="table table-bordered mt-3" id="data-buku">
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#data-buku").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.subscription-packages.books', $subscriptionPackage->id) }}",
                columns: [{
                        //
                        data: 'id',
                        name: 'id',
                        searchable: false,
                        orderable: false,
                        // sama kaya editColumn di serverside
                        render: function(data) {
                            // (data) isinya dari data: 'id'
                            // name pake [] karena yg diceklis bisa lebih dari 1 (array)
                            return `<input type="checkbox" value="${data}" name="book_id[]"
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
            })
        });
    </script>
@endpush
