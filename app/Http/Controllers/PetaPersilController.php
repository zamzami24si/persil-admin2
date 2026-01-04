<?php
// app/Http/Controllers/PetaPersilController.php

namespace App\Http\Controllers;

use App\Models\PetaPersil;
use App\Models\Persil;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PetaPersilController extends Controller
{
    public function index(Request $request)
    {
        $searchableColumns = ['panjang_m', 'lebar_m'];

        $peta = PetaPersil::with(['persil.pemilik'])
            ->search($request, $searchableColumns)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.peta-persil.index', compact('peta'));
    }

    public function create($persil_id)
    {
        $persil = Persil::with('pemilik')->findOrFail($persil_id);
        return view('pages.peta-persil.create', compact('persil'));
    }

    public function store(Request $request, $persil_id)
    {
        $validator = Validator::make($request->all(), [
            'geojson' => 'nullable|json',
            'panjang_m' => 'nullable|numeric|min:0',
            'lebar_m' => 'nullable|numeric|min:0',
            'peta_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,svg|max:2048',
        ], [
            'geojson.json' => 'Format GeoJSON tidak valid',
            'panjang_m.numeric' => 'Panjang harus berupa angka',
            'lebar_m.numeric' => 'Lebar harus berupa angka',
            'panjang_m.min' => 'Panjang tidak boleh negatif',
            'lebar_m.min' => 'Lebar tidak boleh negatif',
            'peta_files.*.mimes' => 'Format file harus JPG, JPEG, PNG, PDF, atau SVG',
            'peta_files.*.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        // Cek apakah sudah ada peta untuk persil ini
        $existingPeta = PetaPersil::where('persil_id', $persil_id)->first();

        if ($existingPeta) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sudah ada data peta untuk persil ini. Silakan edit data yang sudah ada.');
        }

        $peta = PetaPersil::create([
            'persil_id' => $persil_id,
            'geojson' => $request->geojson,
            'panjang_m' => $request->panjang_m,
            'lebar_m' => $request->lebar_m,
        ]);

        // ===== UPLOAD FILE PETA MENGGUNAKAN METODE DARI MODEL =====
        if ($request->hasFile('peta_files')) {
            foreach ($request->file('peta_files') as $index => $file) {
                if ($file->isValid()) {
                    $peta->uploadPetaFile($file, 'Peta ' . ($index + 1));
                }
            }
        }

        return redirect()->route('peta-persil.index')
            ->with('success', 'Data peta persil berhasil ditambahkan');
    }

    public function edit($id)
    {
        $peta = PetaPersil::with(['persil.pemilik'])->findOrFail($id);
        $mediaFiles = Media::where('ref_table', 'peta_persil')
                          ->where('ref_id', $id)
                          ->orderBy('sort_order')
                          ->get();

        return view('pages.peta-persil.edit', compact('peta', 'mediaFiles'));
    }

    public function update(Request $request, $id)
    {
        $peta = PetaPersil::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'geojson' => 'nullable|json',
            'panjang_m' => 'nullable|numeric|min:0',
            'lebar_m' => 'nullable|numeric|min:0',
            'peta_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,svg|max:2048',
        ], [
            'geojson.json' => 'Format GeoJSON tidak valid',
            'panjang_m.numeric' => 'Panjang harus berupa angka',
            'lebar_m.numeric' => 'Lebar harus berupa angka',
            'panjang_m.min' => 'Panjang tidak boleh negatif',
            'lebar_m.min' => 'Lebar tidak boleh negatif',
            'peta_files.*.mimes' => 'Format file harus JPG, JPEG, PNG, PDF, atau SVG',
            'peta_files.*.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        $peta->update([
            'geojson' => $request->geojson,
            'panjang_m' => $request->panjang_m,
            'lebar_m' => $request->lebar_m,
        ]);

        // ===== UPLOAD FILE BARU MENGGUNAKAN METODE DARI MODEL =====
        if ($request->hasFile('peta_files')) {
            foreach ($request->file('peta_files') as $index => $file) {
                if ($file->isValid()) {
                    $peta->uploadPetaFile($file, 'Peta Tambahan');
                }
            }
        }

        // ===== HAPUS FILE YANG DIPILIH =====
        if ($request->has('delete_media')) {
            foreach ($request->delete_media as $mediaId) {
                $media = Media::find($mediaId);
                if ($media) {
                    Storage::disk('public')->delete($media->file_url);
                    $media->delete();
                }
            }
        }

        return redirect()->route('peta-persil.index')
            ->with('success', 'Data peta persil berhasil diperbarui');
    }

    public function destroy($id)
    {
        $peta = PetaPersil::findOrFail($id);

        // Hapus semua file media terkait
        $mediaFiles = Media::where('ref_table', 'peta_persil')
                          ->where('ref_id', $id)
                          ->get();

        foreach ($mediaFiles as $media) {
            Storage::disk('public')->delete($media->file_url);
            $media->delete();
        }

        $peta->delete();

        return redirect()->route('peta-persil.index')
            ->with('success', 'Data peta persil berhasil dihapus');
    }

    // Method untuk melihat detail peta
    public function show($id)
    {
        $peta = PetaPersil::with(['persil.pemilik'])->findOrFail($id);
        $mediaFiles = Media::where('ref_table', 'peta_persil')
                          ->where('ref_id', $id)
                          ->orderBy('sort_order')
                          ->get();

        return view('pages.peta-persil.show', compact('peta', 'mediaFiles'));
    }

    // Tambahkan method untuk download file
    public function downloadFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);

        if ($media->ref_table !== 'peta_persil') {
            abort(403, 'File tidak dapat diakses');
        }

        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($media->file_url);
    }
}
