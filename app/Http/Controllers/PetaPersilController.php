<?php
// app/Http/Controllers/PetaPersilController.php

namespace App\Http\Controllers;

use App\Models\PetaPersil;
use App\Models\Persil;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Tambahkan DB

class PetaPersilController extends Controller
{
    public function index(Request $request)
    {
        $searchableColumns = ['panjang_m', 'lebar_m'];

        $peta = PetaPersil::with(['persil.pemilik', 'media'])
            ->search($request, $searchableColumns) // Pastikan method scopeSearch ada di model
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
            'geojson' => 'nullable|string',
            'panjang_m' => 'nullable|numeric|min:0',
            'lebar_m' => 'nullable|numeric|min:0',
            'peta_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Terjadi kesalahan validasi');
        }

        $existingPeta = PetaPersil::where('persil_id', $persil_id)->first();
        if ($existingPeta) {
            return redirect()->back()->withInput()->with('error', 'Data peta sudah ada.');
        }

        // Process geojson
        $geojsonData = null;
        if ($request->filled('geojson')) {
            $decoded = json_decode($request->geojson, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $geojsonData = $decoded;
            }
        }

        DB::beginTransaction();
        try {
            $peta = PetaPersil::create([
                'persil_id' => $persil_id,
                'geojson' => $geojsonData,
                'panjang_m' => $request->panjang_m,
                'lebar_m' => $request->lebar_m,
                // HAPUS 'luas_dari_dimensi' DISINI
            ]);

            if ($request->hasFile('peta_files')) {
                foreach ($request->file('peta_files') as $index => $file) {
                    if ($file->isValid()) {
                        $peta->uploadPetaFile($file, 'Peta - ' . ($index + 1));
                    }
                }
            }

            DB::commit();
            return redirect()->route('peta-persil.show', $peta->peta_id)
                ->with('success', 'Data peta persil berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $peta = PetaPersil::with(['persil.pemilik', 'media'])->findOrFail($id);
        $mediaFiles = $peta->media;

        return view('pages.peta-persil.edit', compact('peta', 'mediaFiles'));
    }

    public function update(Request $request, $id)
    {
        $peta = PetaPersil::with('media')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'geojson' => 'nullable|string',
            'panjang_m' => 'nullable|numeric|min:0',
            'lebar_m' => 'nullable|numeric|min:0',
            'peta_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal');
        }

        $geojsonData = null;
        if ($request->filled('geojson')) {
            $decoded = json_decode($request->geojson, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $geojsonData = $decoded;
            }
        }

        DB::beginTransaction();
        try {
            $peta->update([
                'geojson' => $geojsonData,
                'panjang_m' => $request->panjang_m,
                'lebar_m' => $request->lebar_m,
                // HAPUS 'luas_dari_dimensi' DISINI JUGA
            ]);

            if ($request->hasFile('peta_files')) {
                foreach ($request->file('peta_files') as $file) {
                    if ($file->isValid()) {
                        $peta->uploadPetaFile($file, 'Peta Tambahan');
                    }
                }
            }

            if ($request->has('delete_media')) {
                foreach ($request->delete_media as $mediaId) {
                    $media = Media::find($mediaId);
                    if ($media && $media->ref_table === 'peta_persil' && $media->ref_id == $id) {
                        Storage::disk('public')->delete($media->file_url);
                        $media->delete();
                    }
                }
            }

            DB::commit();
            return redirect()->route('peta-persil.show', $id)
                ->with('success', 'Data peta persil berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $peta = PetaPersil::with('media')->findOrFail($id);

        foreach ($peta->media as $media) {
            Storage::disk('public')->delete($media->file_url);
            $media->delete();
        }

        $peta->delete();

        return redirect()->route('peta-persil.index')
            ->with('success', 'Data peta persil berhasil dihapus');
    }

    public function show($id)
    {
        $peta = PetaPersil::with(['persil.pemilik', 'media'])->findOrFail($id);
        $mediaFiles = $peta->media;

        return view('pages.peta-persil.show', compact('peta', 'mediaFiles'));
    }

    // ===== HELPER METHODS (Preview & Download) =====

    public function downloadFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File fisik tidak ditemukan.');
        }
        return Storage::disk('public')->download($media->file_url);
    }

    public function previewFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File fisik tidak ditemukan.');
        }
        return response()->file(Storage::disk('public')->path($media->file_url));
    }
}
