@extends('layouts.app')

@section('title', 'Edit Formulir - HAMORA')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bukti.index') }}">Pengumpulan Data</a></li>
                    <li class="breadcrumb-item active">Edit Formulir</li>
                </ol>
            </div>
            <h4 class="page-title">Edit Formulir</h4>
        </div>
    </div>
</div>

@include('bukti._form', ['rekapBukti' => $rekapBukti])
@endsection
