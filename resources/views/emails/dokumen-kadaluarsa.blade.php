@php
    use Illuminate\Support\Number;
@endphp

<x-mail::message>
# ⏰ Notifikasi Dokumen Kadaluarsa

Berikut adalah **{{ Number::format($count) }}** dokumen yang telah melewati masa berlaku per tanggal **{{ now()->format('d/m/Y') }}**:

<x-mail::table>
| No | Nomor Dokumen | Nama Dokumen | Tgl. Terbit | Tgl. Berlaku |
|:--:|:--------------|:--------------|:-----------:|:------------:|
@foreach($documents as $i => $doc)
| {{ Number::format($i + 1) }} | {{ $doc->nomor_dokumen }} | {{ $doc->nama_dokumen }} | {{ optional($doc->tanggal_terbit)->format('d/m/Y') ?? '-' }} | {{ optional($doc->tanggal_berlaku)->format('d/m/Y') ?? '-' }} |
@endforeach
</x-mail::table>

<x-mail::button :url="url('/documents/status/kadaluarsa')" color="danger">
Lihat Dokumen Kadaluarsa
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
