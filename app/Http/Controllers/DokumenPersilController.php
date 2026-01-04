<?php
// app/Http/Controllers/DokumenPersilController.php

namespace App\Http\Controllers;

use App\Models\DokumenPersil;
use App\Models\Persil;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DokumenPersilController extends Controller
{
    public function index(Request $request)
    {
        $filterableColumns = ['jenis_dokumen'];
        $searchableColumns = ['nomor', 'keterangan'];

        $query = DokumenPersil::with(['persil.pemilik']);

        // Apply filters
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->$column);
            }
        }

        // Apply search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                }
            });
        }

        $dokumen = $query->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->withQueryString();

        $jenisDokumenOptions = DokumenPersil::distinct()->pluck('jenis_dokumen');

        return view('pages.dokumen-persil.index', compact('dokumen', 'jenisDokumenOptions'));
    }

    public function create(Request $request)
    {
        // Jika ada parameter persil_id
        if ($persil_id = $request->route('persil_id')) {
            $persil = Persil::with('pemilik')->findOrFail($persil_id);
            return view('pages.dokumen-persil.create', compact('persil'));
        }

        // Jika tidak ada parameter, tampilkan halaman pilih persil
        $persilOptions = Persil::with('pemilik')->orderBy('kode_persil')->paginate(12);
        return view('pages.dokumen-persil.select-persil', compact('persilOptions'));
    }

    public function store(Request $request, $persil_id)
    {
        $validator = Validator::make($request->all(), [
            'jenis_dokumen' => 'required|max:100',
            'nomor' => 'required|max:100',
            'keterangan' => 'nullable',
            'dokumen_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        // Cek apakah nomor dokumen sudah ada untuk persil ini
        $existingDokumen = DokumenPersil::where('persil_id', $persil_id)
            ->where('nomor', $request->nomor)
            ->first();

        if ($existingDokumen) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor dokumen sudah ada untuk persil ini');
        }

        // Simpan data dokumen
        $dokumen = DokumenPersil::create([
            'persil_id' => $persil_id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'nomor' => $request->nomor,
            'keterangan' => $request->keterangan,
        ]);

        // ===== UPLOAD FILE DOKUMEN MENGGUNAKAN METODE DARI MODEL =====
        if ($request->hasFile('dokumen_files')) {
            foreach ($request->file('dokumen_files') as $index => $file) {
                if ($file->isValid()) {
                    $dokumen->uploadDokumenFile($file, 'Dokumen ' . $request->jenis_dokumen . ' - ' . ($index + 1));
                }
            }
        }

        return redirect()->route('dokumen-persil.index')
            ->with('success', 'Dokumen persil berhasil ditambahkan');
    }

    public function edit($id)
    {
        $dokumen = DokumenPersil::with(['persil.pemilik'])->findOrFail($id);

        // Ambil file media untuk dokumen ini
        $mediaFiles = Media::where('ref_table', 'dokumen_persil')
                          ->where('ref_id', $id)
                          ->orderBy('sort_order')
                          ->get();

        return view('pages.dokumen-persil.edit', compact('dokumen', 'mediaFiles'));
    }

    public function update(Request $request, $id)
    {
        $dokumen = DokumenPersil::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'jenis_dokumen' => 'required|max:100',
            'nomor' => 'required|max:100',
            'keterangan' => 'nullable',
            'dokumen_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        // Cek apakah nomor dokumen sudah ada untuk dokumen lain
        $existingDokumen = DokumenPersil::where('persil_id', $dokumen->persil_id)
            ->where('nomor', $request->nomor)
            ->where('dokumen_id', '!=', $id)
            ->first();

        if ($existingDokumen) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor dokumen sudah ada untuk persil ini');
        }

        $dokumen->update($request->except('dokumen_files', 'delete_media'));

        // ===== UPLOAD FILE BARU MENGGUNAKAN METODE DARI MODEL =====
        if ($request->hasFile('dokumen_files')) {
            foreach ($request->file('dokumen_files') as $index => $file) {
                if ($file->isValid()) {
                    $dokumen->uploadDokumenFile($file, 'Dokumen ' . $request->jenis_dokumen . ' - Tambahan');
                }
            }
        }

        // ===== HAPUS FILE YANG DIPILIH =====
        if ($request->has('delete_media')) {
            foreach ($request->delete_media as $mediaId) {
                $media = Media::find($mediaId);
                if ($media) {
                    // Hapus file dari storage
                    Storage::disk('public')->delete($media->file_url);
                    // Hapus dari database
                    $media->delete();
                }
            }
        }

        return redirect()->route('dokumen-persil.index')
            ->with('success', 'Dokumen persil berhasil diperbarui');
    }

    public function destroy($id)
    {
        $dokumen = DokumenPersil::findOrFail($id);

        // Hapus semua file media terkait
        $mediaFiles = Media::where('ref_table', 'dokumen_persil')
                          ->where('ref_id', $id)
                          ->get();

        foreach ($mediaFiles as $media) {
            Storage::disk('public')->delete($media->file_url);
            $media->delete();
        }

        // Hapus data dokumen
        $dokumen->delete();

        return redirect()->route('dokumen-persil.index')
            ->with('success', 'Dokumen persil berhasil dihapus');
    }

    public function show($id)
    {
        $dokumen = DokumenPersil::with(['persil.pemilik'])->findOrFail($id);

        // Ambil semua file media untuk dokumen ini
        $mediaFiles = Media::where('ref_table', 'dokumen_persil')
                          ->where('ref_id', $id)
                          ->orderBy('sort_order')
                          ->get();

        return view('pages.dokumen-persil.show', compact('dokumen', 'mediaFiles'));
    }

    // ===== HELPER METHODS =====
    public function downloadFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);

        // Pastikan file ada
        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($media->file_url);
    }
}
