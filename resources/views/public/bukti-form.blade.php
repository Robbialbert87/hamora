@extends('layouts.public')

@section('title', $rekap->nama . ' - HAMORA')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="card public-card">
            <div class="card-header py-3 px-4">
                <h5 class="mb-0 fw-semibold" style="font-size: 17px;">{{ $rekap->nama }}</h5>
                @if ($rekap->deskripsi)
                    <p class="text-muted mb-0 mt-1" style="font-size: 13px;">{{ $rekap->deskripsi }}</p>
                @endif
            </div>
            <div class="card-body px-4 py-4">
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="ti ti-alert-circle mt-1"></i>
                        <div>
                            <strong>Data belum bisa dikirim.</strong> Periksa kembali isian di bawah:
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('bukti.publik.store', $rekap->token) }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="honeypot-field" aria-hidden="true">
                        <label>Jangan isi kolom ini</label>
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="row g-3">
                        @foreach ($rekap->fields as $field)
                            @php
                                $name = 'field_' . $field->id;
                                $label = $field->label . ($field->required ? ' <span class="required-mark">*</span>' : '');
                            @endphp

                            @if ($field->tipe === 'textarea')
                                <div class="col-12">
                                    <label class="form-label">{!! $label !!}</label>
                                    <textarea name="{{ $name }}" rows="3" class="form-control @error($name) is-invalid @enderror" placeholder="{{ $field->label }}">{{ old($name) }}</textarea>
                                    @error($name)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif ($field->tipe === 'number')
                                <div class="col-12 col-md-6">
                                    <label class="form-label">{!! $label !!}</label>
                                    <input type="number" step="any" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
                                        value="{{ old($name) }}" placeholder="0">
                                    @error($name)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif ($field->tipe === 'date')
                                <div class="col-12 col-md-6">
                                    <label class="form-label">{!! $label !!}</label>
                                    <input type="date" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
                                        value="{{ old($name) }}">
                                    @error($name)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif ($field->tipe === 'select')
                                <div class="col-12 col-md-6">
                                    <label class="form-label">{!! $label !!}</label>
                                    <select name="{{ $name }}" class="form-select @error($name) is-invalid @enderror">
                                        <option value="">-- Pilih {{ $field->label }} --</option>
                                        @foreach ($field->options ?? [] as $option)
                                            <option value="{{ $option }}" {{ old($name) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    @error($name)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif ($field->tipe === 'file')
                                <div class="col-12">
                                    <label class="form-label">{!! $label !!}</label>
                                    <input type="file" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="form-text">Format: PDF / JPG / PNG &middot; Maks: 5MB</div>
                                    @error($name)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <div class="col-12 col-md-6">
                                    <label class="form-label">{!! $label !!}</label>
                                    <input type="text" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
                                        value="{{ old($name) }}" placeholder="{{ $field->label }}">
                                    @error($name)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" style="background:#5156be; border-color:#5156be;">
                            <i class="ti ti-send me-1"></i> Kirim Data
                        </button>
                    </div>
                    <p class="text-muted text-center mt-3 mb-0" style="font-size: 12px;">Dengan mengirim, Anda setuju data di atas dicatat untuk keperluan rekapitulasi resmi.</p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
