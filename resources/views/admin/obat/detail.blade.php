<x-layouts.app title="Detail Obat">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">

                {{-- ALERT FLASH MESSAGE --}}
                @if (session('message'))
                    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h1 class="mb-4">Detail Obat</h1>

                <div class="card mb-4">
                    <div class="card-body">
                        <strong>Nama:</strong> {{ $obat->nama_obat }} <br>
                        <strong>Kemasan:</strong> {{ $obat->kemasan }} <br>
                        <strong>Harga:</strong> {{ 'Rp ' . number_format($obat->harga, 0, ',', '.') }} <br>
                        <strong>Stok:</strong> {{ $obat->stok }}
                    </div>
                </div>

                <h4>Kelola Stok</h4>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('obat.updateStok', $obat->id) }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control"
                                    min="1" required>
                            </div>

                            <div class="d-flex gap-2">
                                <button name="action" value="tambah" class="btn btn-success">
                                    + Tambah Stok
                                </button>

                                <button name="action" value="kurang" class="btn btn-danger">
                                    - Kurangi Stok
                                </button>
                            </div>
                        </form>

                        <a href="{{ route('obat.index') }}" class="btn btn-secondary mt-3">
                            Kembali
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
