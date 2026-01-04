<?php
// app/Http/Controllers/JenisPenggunaanController.php

namespace App\Http\Controllers;

use App\Models\JenisPenggunaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisPenggunaanController extends Controller
{
    public function index(Request $request)
    {
        $searchableColumns = ['nama_penggunaan', 'keterangan'];

        $jenisPenggunaan = JenisPenggunaan::withCount('persil')
            ->search($request, $searchableColumns)
            ->orderBy('nama_penggunaan')
            ->paginate(10)
            ->withQueryString();

        return view('pages.jenis-penggunaan.index', compact('jenisPenggunaan'));
    }

    public function create()
    {
        return view('pages.jenis-penggunaan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_penggunaan' => 'required|max:100|unique:jenis_penggunaan,nama_penggunaan',
            'keterangan' => 'nullable',
        ], [
            'nama_penggunaan.required' => 'Nama penggunaan harus diisi',
            'nama_penggunaan.max' => 'Nama penggunaan maksimal 100 karakter',
            'nama_penggunaan.unique' => 'Nama penggunaan sudah ada',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        JenisPenggunaan::create([
            'nama_penggunaan' => $request->nama_penggunaan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('jenis-penggunaan.index')
            ->with('success', 'Jenis penggunaan berhasil ditambahkan');
    }

    public function show($id)
    {
        $jenis = JenisPenggunaan::with(['persil.pemilik'])->findOrFail($id);
        return view('pages.jenis-penggunaan.show', compact('jenis'));
    }

    public function edit($id)
    {
        $jenis = JenisPenggunaan::findOrFail($id);
        return view('pages.jenis-penggunaan.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $jenis = JenisPenggunaan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_penggunaan' => 'required|max:100|unique:jenis_penggunaan,nama_penggunaan,' . $jenis->jenis_id . ',jenis_id',
            'keterangan' => 'nullable',
        ], [
            'nama_penggunaan.required' => 'Nama penggunaan harus diisi',
            'nama_penggunaan.max' => 'Nama penggunaan maksimal 100 karakter',
            'nama_penggunaan.unique' => 'Nama penggunaan sudah ada',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        $jenis->update([
            'nama_penggunaan' => $request->nama_penggunaan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('jenis-penggunaan.index')
            ->with('success', 'Jenis penggunaan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jenis = JenisPenggunaan::findOrFail($id);

        // Cek apakah jenis penggunaan digunakan di persil
        if ($jenis->persil()->count() > 0) {
            return redirect()->route('jenis-penggunaan.index')
                ->with('error', 'Tidak dapat menghapus jenis penggunaan karena masih digunakan di data persil');
        }

        $jenis->delete();

        return redirect()->route('jenis-penggunaan.index')
            ->with('success', 'Jenis penggunaan berhasil dihapus');
    }
}
