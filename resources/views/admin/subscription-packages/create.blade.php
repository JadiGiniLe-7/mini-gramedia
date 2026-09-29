@extends('layout.app')

@section('content')
    <div class="card mt-5 w-50 d-block mx-auto">
        <div class="card-header">
            <h1>Tambah Paket Langganan</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subscription-packages.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Paket</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga Paket</label>
                    <input type="number" name="price" id="price"
                        class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Paket</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                        rows="3" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label">Warna Paket</label>
                    <input type="color" name="color" id="color"
                        class="form-control form-control-color @error('color') is-invalid @enderror"
                        value="{{ old('color', '#000000') }}" required>
                    <input type="text" name="color-text" id="color-text"
                        class="form-control @error('color') is-invalid @enderror"
                        value="{{ old('color', '#000000') }}" required>
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        const colorInput = document.getElementById('color');
        const colorText = document.getElementById('color-text');

        colorInput.addEventListener('input', () => {
            colorText.value = colorInput.value;
        });

        colorText.addEventListener('input', () => {
            const colorValue = colorText.value.trim();

            if (/^#[0-9a-fA-F]{6}$/.test(colorValue)) {
                colorInput.value = colorValue;
                return;
            }

            const colorProbe = document.createElement('span');
            colorProbe.style.color = colorValue;
            document.body.appendChild(colorProbe);
            const rgb = getComputedStyle(colorProbe).color.match(/\d+/g);
            colorProbe.remove();

            if (rgb && rgb.length === 3) {
                colorInput.value = '#' + rgb.map(value => Number(value).toString(16).padStart(2, '0')).join('');
            }
        });
    </script>
@endsection
