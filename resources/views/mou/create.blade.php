@extends('layouts.app')

@section('title', 'Upload MOU - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mou.index') }}">MOU</a></li>
                    <li class="breadcrumb-item active">Upload</li>
                </ol>
            </div>
            <h4 class="page-title">Upload MOU</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="card-title mb-0">Form Upload MOU</h4>
                    <p class="text-muted mb-0">Lengkapi form di bawah untuk upload MOU / perjanjian kerja sama</p>
                </div>

                <form action="{{ route('mou.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Pihak <span class="text-danger">*</span></label>
                                <input type="text" name="pihak"
                                    class="form-control @error('pihak') is-invalid @enderror"
                                    value="{{ old('pihak') }}" required
                                    placeholder="Nama pihak / instansi yang ber-MOU">
                                @error('pihak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Judul MOU <span class="text-danger">*</span></label>
                                <input type="text" name="judul"
                                    class="form-control @error('judul') is-invalid @enderror"
                                    value="{{ old('judul') }}" required
                                    placeholder="Judul MOU / perjanjian kerja sama">
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Bidang</label>
                                <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror">
                                    <option value="">Pilih Bidang</option>
                                    @foreach ($bidang as $b)
                                        <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                                    @endforeach
                                </select>
                                @error('bidang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategori as $k)
                                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nomor MOU <span class="text-danger">*</span></label>
                                <input type="text" name="nomor"
                                    class="form-control @error('nomor') is-invalid @enderror"
                                    value="{{ old('nomor') }}" required
                                    placeholder="Contoh: MOU/001/HAMORA/2024">
                                @error('nomor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Mulai Perjanjian <span class="text-danger">*</span></label>
                                <input type="date" name="mulai_perjanjian" id="mulai_perjanjian"
                                    class="form-control @error('mulai_perjanjian') is-invalid @enderror"
                                    value="{{ old('mulai_perjanjian') }}" required>
                                @error('mulai_perjanjian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Masa Berlaku (Tahun)</label>
                                <select name="masa_berlaku" id="masa_berlaku"
                                    class="form-select @error('masa_berlaku') is-invalid @enderror">
                                    <option value="">Otomatis dari tanggal akhir</option>
                                    <option value="30" {{ old('masa_berlaku') == 30 ? 'selected' : '' }}>1 Bulan</option>
                                    <option value="90" {{ old('masa_berlaku') == 90 ? 'selected' : '' }}>3 Bulan</option>
                                    <option value="180" {{ old('masa_berlaku') == 180 ? 'selected' : '' }}>6 Bulan</option>
                                    <option value="365" {{ old('masa_berlaku') == 365 ? 'selected' : '' }}>1 Tahun</option>
                                    <option value="730" {{ old('masa_berlaku') == 730 ? 'selected' : '' }}>2 Tahun</option>
                                    <option value="1095" {{ old('masa_berlaku') == 1095 ? 'selected' : '' }}>3 Tahun</option>
                                    <option value="1460" {{ old('masa_berlaku') == 1460 ? 'selected' : '' }}>4 Tahun</option>
                                    <option value="1825" {{ old('masa_berlaku') == 1825 ? 'selected' : '' }}>5 Tahun</option>
                                    <option value="2190" {{ old('masa_berlaku') == 2190 ? 'selected' : '' }}>6 Tahun</option>
                                    <option value="2555" {{ old('masa_berlaku') == 2555 ? 'selected' : '' }}>7 Tahun</option>
                                    <option value="2920" {{ old('masa_berlaku') == 2920 ? 'selected' : '' }}>8 Tahun</option>
                                    <option value="3285" {{ old('masa_berlaku') == 3285 ? 'selected' : '' }}>9 Tahun</option>
                                    <option value="3650" {{ old('masa_berlaku') == 3650 ? 'selected' : '' }}>10 Tahun</option>
                                </select>
                                @error('masa_berlaku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Akhir Perjanjian</label>
                                <input type="date" name="akhir_perjanjian" id="akhir_perjanjian"
                                    class="form-control @error('akhir_perjanjian') is-invalid @enderror"
                                    value="{{ old('akhir_perjanjian') }}">
                                @error('akhir_perjanjian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Keterangan</label>
                                <input type="text" id="keterangan" class="form-control" readonly
                                    placeholder="Otomatis terhitung">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="kadaluarsa" {{ old('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                                    <option value="dicabut" {{ old('status') == 'dicabut' ? 'selected' : '' }}>Dicabut</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">File PDF <span class="text-danger">*</span></label>
                                <input type="file" name="file_pdf"
                                    class="form-control @error('file_pdf') is-invalid @enderror"
                                    accept="application/pdf" required>
                                @error('file_pdf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="text-muted mt-1" style="font-size: 12px;">Format: PDF, Maks: 20MB</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
                        <a href="{{ route('mou.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mulaiInput = document.getElementById('mulai_perjanjian');
        var masaBerlakuInput = document.getElementById('masa_berlaku');
        var akhirInput = document.getElementById('akhir_perjanjian');
        var keteranganInput = document.getElementById('keterangan');
        var manualAkhir = false;

        function hitungSelisihDuaTanggal(mulai, akhir) {
            if (!mulai || !akhir) return { years: 0, months: 0, days: 0 };
            var m = new Date(mulai), a = new Date(akhir);
            var years = a.getFullYear() - m.getFullYear();
            var months = a.getMonth() - m.getMonth();
            var days = a.getDate() - m.getDate();
            if (days < 0) { months--; var prevMonth = new Date(a.getFullYear(), a.getMonth(), 0); days += prevMonth.getDate(); }
            if (months < 0) { years--; months += 12; }
            return { years: Math.max(0, years), months: Math.max(0, months), days: Math.max(0, days) };
        }

        function hitungSisaDariHariIni(akhir) {
            if (!akhir) return { years: 0, months: 0, days: 0 };
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            var a = new Date(akhir);
            a.setHours(0, 0, 0, 0);
            if (today >= a) return { years: 0, months: 0, days: 0 };
            return hitungSelisihDuaTanggal(today.toISOString().slice(0,10), akhir);
        }

        function formatKeterangan(years, months, days) {
            return years + ' Tahun ' + months + ' Bulan ' + days + ' Hari';
        }

        function updateDariMasaBerlaku() {
            var mulai = mulaiInput.value;
            var hari = parseInt(masaBerlakuInput.value);

            if (mulai && hari) {
                var date = new Date(mulai);
                date.setDate(date.getDate() + hari);
                var y = date.getFullYear();
                var m = String(date.getMonth() + 1).padStart(2, '0');
                var d = String(date.getDate()).padStart(2, '0');
                akhirInput.value = y + '-' + m + '-' + d;

                var sisa = hitungSisaDariHariIni(akhirInput.value);
                keteranganInput.value = formatKeterangan(sisa.years, sisa.months, sisa.days);
            }
        }

        function updateDariAkhir() {
            var mulai = mulaiInput.value;
            var akhir = akhirInput.value;

            if (mulai && akhir) {
                if (akhir < mulai) {
                    keteranganInput.value = 'Akhir harus setelah Mulai';
                    return;
                }
                var sisa = hitungSisaDariHariIni(akhir);
                keteranganInput.value = formatKeterangan(sisa.years, sisa.months, sisa.days);
            }
        }

        mulaiInput.addEventListener('change', function() {
            if (masaBerlakuInput.value) {
                updateDariMasaBerlaku();
            } else if (akhirInput.value) {
                updateDariAkhir();
            }
        });

        masaBerlakuInput.addEventListener('change', function() {
            if (this.value) {
                manualAkhir = false;
                updateDariMasaBerlaku();
            } else if (akhirInput.value) {
                updateDariAkhir();
            } else {
                akhirInput.value = '';
                keteranganInput.value = '';
            }
        });

        akhirInput.addEventListener('change', function() {
            if (this.value) {
                manualAkhir = true;
                updateDariAkhir();
            } else if (masaBerlakuInput.value) {
                updateDariMasaBerlaku();
            } else {
                keteranganInput.value = '';
            }
        });
    });
</script>
@endsection