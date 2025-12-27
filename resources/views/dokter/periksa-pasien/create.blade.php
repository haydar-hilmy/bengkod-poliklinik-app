<x-layouts.app title="Periksa Pasien">
    {{-- ALERT FLASH MESSAGE --}}
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Periksa Pasien</h1>

                @if (session('message'))
                    <div class="alert alert-{{ session('type', 'danger') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('periksa-pasien.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                            <div class="form-group mb-3">
                                <label for="obat" class="form-label">Pilih Obat</label>
                                <select id="select-obat" class="form-select">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}"
                                                data-nama="{{ $obat->nama_obat }}"
                                                data-harga="{{ $obat->harga }}"
                                                data-stok="{{ $obat->stok }}"
                                                @if($obat->stok == 0) disabled @endif>
                                            {{ $obat->nama_obat }}
                                            - Rp{{ number_format($obat->harga) }}
                                            @if($obat->stok == 0) (Habis) @endif
                                            @if($obat->stok <= 5 && $obat->stok > 0) (Menipis) @endif
                                        </option>

                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2"></ul>
                                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                <input type="hidden" name="obat_json" id="obat_json">
                            </div>

                            <div class="form-group mb-3">
                                <label>Total Harga</label>
                                <p id="total-harga" class="fw-bold">Rp 0</p>
                            </div>

                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('periksa-pasien.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<script>
    const selectObat = document.getElementById('select-obat');
    const listObat = document.getElementById('obat-terpilih');
    const inputBiaya = document.getElementById('biaya_periksa');
    const inputObatJson = document.getElementById('obat_json');
    const totalHargaEl = document.getElementById('total-harga');

    let daftarObat = [];

    selectObat.addEventListener('change', () => {
        const selectedOption = selectObat.options[selectObat.selectedIndex];
        const id = selectedOption.value;
        const nama = selectedOption.dataset.nama;
        const harga = parseInt(selectedOption.dataset.harga || 0);

        if (!id || daftarObat.some(o => o.id == id)) {
            return;
        }

        const stok = parseInt(selectedOption.dataset.stok || 0);

        if (stok === 0) {
            alert(`Stok ${nama} sudah habis!`);
            return;
        }

        daftarObat.push({
            id,
            nama,
            harga,
            jumlah: 1
        });
        renderObat();
        selectObat.selectedIndex = 0;
    });
    function renderObat() {
        listObat.innerHTML = '';
        let total = 0;

        daftarObat.forEach((obat, index) => {
            total += obat.harga * obat.jumlah;

            const item = document.createElement('li');
            item.className = 'list-group-item d-flex justify-content-between align-items-center';
            item.innerHTML = `
                ${obat.nama} - Rp ${obat.harga.toLocaleString()}
                <input type="number" min="1" value="${obat.jumlah}" 
                    class="form-control form-control-sm w-25 mx-2"
                    onchange="ubahJumlah(${index}, this.value)">
                <button type="button" class="btn btn-sm btn-danger" onclick="hapusObat(${index})">Hapus</button>
            `;
            listObat.appendChild(item);
        });

        inputBiaya.value = total;
        totalHargaEl.textContent = `Rp ${total.toLocaleString()}`;
        inputObatJson.value = JSON.stringify(daftarObat);
    }

    function ubahJumlah(index, value) {
        daftarObat[index].jumlah = parseInt(value);
        renderObat();
    }

    function hapusObat(index) {
        daftarObat.splice(index, 1);
        renderObat();
    }
</script>