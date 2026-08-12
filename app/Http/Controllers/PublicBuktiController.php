<?php
namespace App\Http\Controllers;

use App\Models\RekapBukti;
use App\Models\RekapBuktiRecord;
use App\Models\RekapBuktiFile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PublicBuktiController extends Controller
{
    public function form($token)
    {
        $rekap = RekapBukti::with('fields')->where('token', $token)->first();

        if (! $rekap) {
            return response()->view('public.bukti-tidak-aktif', [
                'pesan' => 'Formulir tidak ditemukan.',
            ], 404);
        }

        if ($rekap->status !== 'aktif') {
            return view('public.bukti-tidak-aktif', [
                'pesan' => 'Link ini sedang ditutup oleh admin.',
            ]);
        }

        return view('public.bukti-form', compact('rekap'));
    }

    public function sukses($token)
    {
        $rekap = RekapBukti::where('token', $token)->first();

        if (! $rekap) {
            return response()->view('public.bukti-tidak-aktif', [
                'pesan' => 'Formulir tidak ditemukan.',
            ], 404);
        }

        return view('public.bukti-sukses', compact('rekap'));
    }

    public function store(Request $request, $token)
    {
        $rekap = RekapBukti::with('fields')->where('token', $token)->first();

        if (! $rekap) {
            return response()->view('public.bukti-tidak-aktif', [
                'pesan' => 'Formulir tidak ditemukan.',
            ], 404);
        }

        if ($rekap->status !== 'aktif') {
            return view('public.bukti-tidak-aktif', [
                'pesan' => 'Link ini sedang ditutup oleh admin.',
            ]);
        }

        if ($request->filled('website')) {
            return redirect()->route('bukti.publik.sukses', $token);
        }

        $fields = $rekap->fields;
        $rules = [];
        $messages = [];

        foreach ($fields as $field) {
            $name = 'field_' . $field->id;
            $label = $field->label;

            $fieldRules = [];
            $fieldRules[] = $field->required ? 'required' : 'nullable';

            switch ($field->tipe) {
                case 'text':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:255';
                    break;
                case 'textarea':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:5000';
                    break;
                case 'number':
                    $fieldRules[] = 'numeric';
                    break;
                case 'date':
                    $fieldRules[] = 'date';
                    break;
                case 'select':
                    $fieldRules[] = 'string';
                    if (!empty($field->options)) {
                        $fieldRules[] = 'in:' . implode(',', $field->options);
                    }
                    break;
                case 'file':
                    $fieldRules[] = 'file';
                    $fieldRules[] = 'mimes:pdf,jpg,jpeg,png';
                    $fieldRules[] = 'max:5120';
                    break;
            }

            $rules[$name] = implode('|', $fieldRules);
            $messages[$name . '.required'] = 'Kolom "' . $label . '" wajib diisi.';
            $messages[$name . '.string'] = 'Kolom "' . $label . '" harus berupa teks.';
            $messages[$name . '.numeric'] = 'Kolom "' . $label . '" harus berupa angka.';
            $messages[$name . '.date'] = 'Kolom "' . $label . '" harus berupa tanggal yang valid.';
            $messages[$name . '.in'] = 'Pilihan pada kolom "' . $label . '" tidak valid.';
            $messages[$name . '.file'] = 'Kolom "' . $label . '" harus berupa file.';
            $messages[$name . '.mimes'] = 'File pada kolom "' . $label . '" harus berformat PDF atau JPG/PNG.';
            $messages[$name . '.max'] = 'Ukuran file pada kolom "' . $label . '" maksimal 5MB.';
        }

        $request->validate($rules, $messages);

        $data = [];
        foreach ($fields as $field) {
            $name = 'field_' . $field->id;
            if ($field->tipe === 'file') {
                $data[$field->id] = $request->hasFile($name) ? 'file' : null;
            } else {
                $data[$field->id] = $request->input($name);
            }
        }

        $record = RekapBuktiRecord::create([
            'rekap_bukti_id' => $rekap->id,
            'data' => $data,
        ]);

        foreach ($fields as $field) {
            $name = 'field_' . $field->id;
            if ($field->tipe !== 'file' || !$request->hasFile($name)) {
                continue;
            }

            $uploaded = $request->file($name);
            $path = $uploaded->store('bukti/' . $rekap->id, 'public');

            $buktiFile = RekapBuktiFile::create([
                'record_id' => $record->id,
                'rekap_bukti_id' => $rekap->id,
                'field_id' => $field->id,
                'nama_asli' => $uploaded->getClientOriginalName(),
                'path' => $path,
            ]);

            $data[$field->id] = $buktiFile->id;
        }

        $record->update(['data' => $data]);

        ActivityLog::log('bukti_masuk', "Data bukti masuk: {$rekap->nama}");

        return redirect()->route('bukti.publik.sukses', $token);
    }
}
