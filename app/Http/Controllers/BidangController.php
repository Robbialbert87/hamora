<?php
namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    public function index()
    {
        $bidang = Bidang::withCount('documents')->get();
        return view('bidang.index', compact('bidang'));
    }

    public function create()
    {
        return view('bidang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|unique:bidang|max:255',
            'deskripsi' => 'nullable',
        ]);

        Bidang::create($validated);
        return redirect()->route('bidang.index')->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function edit(Bidang $bidang)
    {
        return view('bidang.edit', compact('bidang'));
    }

    public function update(Request $request, Bidang $bidang)
    {
        $validated = $request->validate([
            'nama' => 'required|unique:bidang,nama,' . $bidang->id . '|max:255',
            'deskripsi' => 'nullable',
        ]);

        $bidang->update($validated);
        return redirect()->route('bidang.index')->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy(Bidang $bidang)
    {
        if ($bidang->documents()->count() > 0) {
            return back()->with('error', 'Bidang tidak bisa dihapus karena masih memiliki dokumen.');
        }
        $bidang->delete();
        return redirect()->route('bidang.index')->with('success', 'Bidang berhasil dihapus.');
    }
}
