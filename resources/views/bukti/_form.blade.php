@php
    $rekap = $rekapBukti ?? null;
    $initialFields = $rekap
        ? $rekap->fields->map(fn($f) => [
            'id' => $f->id,
            'label' => $f->label,
            'tipe' => $f->tipe,
            'required' => (bool) $f->required,
            'options' => $f->options ? implode("\n", $f->options) : '',
        ])->all()
        : [];
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="card-title mb-0">{{ $rekap ? 'Edit Formulir' : 'Buat Formulir' }}</h4>
                    <p class="text-muted mb-0">Tentukan judul, deskripsi, dan kolom data yang ingin dikumpulkan dari pihak luar</p>
                </div>

                <form action="{{ $rekap ? route('bukti.update', $rekap->id) : route('bukti.store') }}" method="POST" novalidate>
                    @csrf
                    @if ($rekap)
                        @method('PUT')
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="form-label">Nama Formulir <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $rekap->nama ?? '') }}" required placeholder="contoh: Pengumpulan Data Pembayaran Pajak 2026">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Status Link Publik</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="aktif" {{ old('status', $rekap->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif (bisa diisi)</option>
                                    <option value="nonaktif" {{ old('status', $rekap->status ?? 'aktif') === 'nonaktif' ? 'selected' : '' }}>Nonaktif (ditutup)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="2"
                                    placeholder="Keterangan singkat yang tampil di halaman form publik...">{{ old('deskripsi', $rekap->deskripsi ?? '') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0" style="font-size: 15px;">Kolom Data</h5>
                            <small class="text-muted">Kolom yang muncul di form publik. File hanya menerima PDF/JPG/PNG maks 5MB.</small>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-field" style="flex: 0 0 auto;"><i class="ti ti-plus me-1"></i> Tambah Kolom</button>
                    </div>

                    <div id="field-container"></div>
                    @error('fields')
                        <div class="text-danger mt-1" style="font-size: 12.5px;">{{ $message }}</div>
                    @enderror

                    <div class="mt-4 text-end">
                        <a href="{{ route('bukti.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-device-floppy me-1"></i> {{ $rekap ? 'Simpan Perubahan' : 'Simpan & Dapatkan Link' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var container = document.getElementById('field-container');
    var tipeLabels = {
        text: 'Teks',
        textarea: 'Teks Panjang',
        number: 'Angka',
        date: 'Tanggal',
        select: 'Pilihan',
        file: 'File (PDF/JPG)'
    };

    function esc(v) {
        return String(v == null ? '' : v)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function reIndex() {
        var rows = container.querySelectorAll('.field-row');
        rows.forEach(function(row, i) {
            var inputs = row.querySelectorAll('[name]');
            inputs.forEach(function(input) {
                var name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/\[\d+\]/g, '[' + i + ']'));
            });
            row.querySelector('.req-check').id = 'req-' + i;
            row.querySelector('.req-label').setAttribute('for', 'req-' + i);
        });
    }

    function addRow(data) {
        data = data || {};
        var i = container.children.length;
        var row = document.createElement('div');
        row.className = 'field-row card mb-2';
        row.style.borderLeft = '3px solid #556ee5';

        var optionsHidden = data.tipe === 'select' ? '' : ' d-none';
        var requiredChecked = data.required ? 'checked' : '';
        var tipeOptions = '';
        Object.keys(tipeLabels).forEach(function(key) {
            var selected = data.tipe === key ? 'selected' : '';
            tipeOptions += '<option value="' + key + '" ' + selected + '>' + tipeLabels[key] + '</option>';
        });

        row.innerHTML =
            '<div class="card-body py-3">' +
                '<div class="row g-2 align-items-end">' +
                    '<div class="col-md-4">' +
                        '<label class="form-label">Label Kolom</label>' +
                        '<input type="text" name="fields[' + i + '][label]" class="form-control form-control-sm" value="' + esc(data.label || '') + '" required placeholder="contoh: NTPN / No. Bukti">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">Jenis</label>' +
                        '<select name="fields[' + i + '][tipe]" class="form-select form-select-sm tipe-select">' + tipeOptions + '</select>' +
                    '</div>' +
                    '<div class="col-md-3 options-wrap' + optionsHidden + '">' +
                        '<label class="form-label">Pilihan <small class="text-muted">(satu per baris)</small></label>' +
                        '<textarea name="fields[' + i + '][options]" class="form-control form-control-sm" rows="2" placeholder="Pilihan 1&#10;Pilihan 2">' + esc(data.options || '') + '</textarea>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                        '<div class="d-flex align-items-center gap-2">' +
                            '<div class="form-check mb-0">' +
                                '<input class="form-check-input req-check" type="checkbox" name="fields[' + i + '][required]" value="1" id="req-' + i + '" ' + requiredChecked + '>' +
                                '<label class="form-check-label req-label" for="req-' + i + '">Wajib</label>' +
                            '</div>' +
                            '<button type="button" class="btn btn-light-danger btn-icon btn-sm btn-remove-field ms-auto" title="Hapus kolom"><i class="ti ti-trash"></i></button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';

        container.appendChild(row);

        row.querySelector('.tipe-select').addEventListener('change', function() {
            row.querySelector('.options-wrap').classList.toggle('d-none', this.value !== 'select');
        });

        row.querySelector('.btn-remove-field').addEventListener('click', function() {
            row.remove();
            reIndex();
        });

        reIndex();
    }

    document.getElementById('btn-add-field').addEventListener('click', function() {
        addRow({});
    });

    var initialFields = @json($initialFields);
    if (initialFields.length === 0) {
        addRow({});
    } else {
        initialFields.forEach(function(field) {
            addRow(field);
        });
    }
})();
</script>
