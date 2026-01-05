<?php

namespace App\Http\Controllers;

use App\Models\Persil;
use App\Models\Warga;
use App\Models\Media;
use App\Models\JenisPenggunaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PersilController extends Controller
{
    public function index(Request $request)
    {
        $filterableColumns = ['penggunaan', 'rt', 'rw'];
        $searchableColumns = ['kode_persil', 'alamat_lahan'];

        $persil = Persil::with('pemilik')
            ->when($request->filled('penggunaan') || $request->filled('rt') || $request->filled('rw'), function ($query) use ($request) {
                return $query->filter($request, ['penggunaan', 'rt', 'rw']);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->search($request, ['kode_persil', 'alamat_lahan']);
            })
            ->orderBy('kode_persil')
            ->paginate(10)
            ->withQueryString();

        $penggunaanOptions = Persil::distinct()->pluck('penggunaan');
        $rtOptions = Persil::distinct()->pluck('rt');
        $rwOptions = Persil::distinct()->pluck('rw');

        return view('pages.persil.index', compact('persil', 'penggunaanOptions', 'rtOptions', 'rwOptions'));
    }

    public function create()
    {
        $wargaOptions = Warga::orderBy('nama')->get();
        $jenisPenggunaanOptions = JenisPenggunaan::orderBy('nama_penggunaan')->get();
        return view('pages.persil.create', compact('wargaOptions', 'jenisPenggunaanOptions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_persil' => 'required|unique:persil|max:50',
            'pemilik_warga_id' => 'required|exists:warga,warga_id',
            'luas_m2' => 'required|numeric|min:0',
            'penggunaan' => 'required|max:100',
            'alamat_lahan' => 'required',
            'rt' => 'required|max:3',
            'rw' => 'required|max:3',
            // 'koordinat' DIHAPUS
            'foto_bidang.*' => 'nullable|image|max:5120',
            'koordinat_files.*' => 'nullable|file|max:5120',
            'file_uploads.*' => 'nullable|file|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Terjadi kesalahan validasi.');
        }

        DB::beginTransaction();
        try {
            $persil = Persil::create($request->except(['file_uploads', 'foto_bidang', 'koordinat_files']));
            $uploadedCount = 0;

            $fileInputs = ['foto_bidang', 'koordinat_files', 'file_uploads'];
            foreach ($fileInputs as $inputName) {
                if ($request->hasFile($inputName)) {
                    foreach ($request->file($inputName) as $index => $file) {
                        if ($file->isValid()) {
                            if (str_starts_with($file->getMimeType(), 'image/')) {
                                $persil->uploadFotoBidang($file, 'Foto - ' . ($index + 1));
                            } else {
                                $persil->uploadKoordinatFile($file, 'Dokumen - ' . ($index + 1));
                            }
                            $uploadedCount++;
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('persil.index')->with('success', "Data persil berhasil disimpan dengan $uploadedCount file.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $persil = Persil::with(['pemilik', 'dokumen', 'media', 'peta'])->findOrFail($id);
        $mediaFiles = Media::where('ref_table', 'persil')->where('ref_id', $id)->orderBy('sort_order')->get();

        $totalFiles = $mediaFiles->count();
        $imageFiles = $mediaFiles->whereIn('mime_type', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])->count();
        $docFiles = $totalFiles - $imageFiles;

        return view('pages.persil.show', compact('persil', 'mediaFiles', 'totalFiles', 'imageFiles', 'docFiles'));
    }

    public function edit($id)
    {
        $persil = Persil::findOrFail($id);
        $wargaOptions = Warga::orderBy('nama')->get();
        $jenisPenggunaanOptions = JenisPenggunaan::orderBy('nama_penggunaan')->get();
        $mediaFiles = Media::where('ref_table', 'persil')->where('ref_id', $id)->orderBy('created_at', 'desc')->get();

        return view('pages.persil.edit', compact('persil', 'wargaOptions', 'jenisPenggunaanOptions', 'mediaFiles'));
    }

    public function update(Request $request, $id)
    {
        $persil = Persil::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_persil' => 'required|max:50|unique:persil,kode_persil,' . $id . ',persil_id',
            'pemilik_warga_id' => 'required|exists:warga,warga_id',
            'luas_m2' => 'required|numeric|min:0',
            'penggunaan' => 'required|max:100',
            'alamat_lahan' => 'required',
            'rt' => 'required|max:3',
            'rw' => 'required|max:3',
            // 'koordinat' DIHAPUS
            'foto_bidang.*' => 'nullable|image|max:5120',
            'koordinat_files.*' => 'nullable|file|max:5120',
            'file_uploads.*' => 'nullable|file|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal.');
        }

        DB::beginTransaction();
        try {
            $persil->update($request->except(['file_uploads', 'foto_bidang', 'koordinat_files', 'delete_media']));

            if ($request->has('delete_media')) {
                foreach ($request->delete_media as $mediaId) {
                    $media = Media::find($mediaId);
                    if ($media && $media->ref_table == 'persil' && $media->ref_id == $id) {
                        Storage::disk('public')->delete($media->file_url);
                        $media->delete();
                    }
                }
            }

            $fileInputs = ['foto_bidang', 'koordinat_files', 'file_uploads'];
            foreach ($fileInputs as $inputName) {
                if ($request->hasFile($inputName)) {
                    foreach ($request->file($inputName) as $file) {
                        if ($file->isValid()) {
                            if (str_starts_with($file->getMimeType(), 'image/')) {
                                $persil->uploadFotoBidang($file, 'Foto Baru');
                            } else {
                                $persil->uploadKoordinatFile($file, 'Dokumen Baru');
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('persil.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $persil = Persil::findOrFail($id);
        $mediaFiles = Media::where('ref_table', 'persil')->where('ref_id', $id)->get();

        foreach ($mediaFiles as $media) {
            Storage::disk('public')->delete($media->file_url);
            $media->delete();
        }

        $persil->delete();
        return redirect()->route('persil.index')->with('success', 'Data berhasil dihapus.');
    }

    public function downloadFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File tidak ditemukan di server.');
        }
        return Storage::disk('public')->download($media->file_url, $media->caption . '.' . pathinfo($media->file_url, PATHINFO_EXTENSION));
    }

    public function previewFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File tidak ditemukan di server.');
        }
        return response()->file(Storage::disk('public')->path($media->file_url));
    }
}
