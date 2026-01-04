<?php
// app/Http/Controllers/PersilController.php

namespace App\Http\Controllers;

use App\Models\Persil;
use App\Models\Warga;
use App\Models\Media;
use App\Models\JenisPenggunaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PersilController extends Controller
{
    public function index(Request $request)
    {
        $filterableColumns = ['penggunaan', 'rt', 'rw'];
        $searchableColumns = ['kode_persil', 'alamat_lahan'];

        $persil = Persil::with('pemilik')
            ->when($request->filled('penggunaan') || $request->filled('rt') || $request->filled('rw'), function ($query) use ($request) {
                // Terapkan filter hanya jika ada parameter filter
                return $query->filter($request, ['penggunaan', 'rt', 'rw']);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                // Terapkan search hanya jika ada parameter search
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
            // Validasi untuk file upload
            'file_uploads.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        // Simpan data persil
        $persil = Persil::create($request->except(['file_uploads']));

        // ===== UPLOAD FILE MENGGUNAKAN METODE DARI MODEL =====
        $uploadedFiles = 0;
        if ($request->hasFile('file_uploads')) {
            foreach ($request->file('file_uploads') as $index => $file) {
                if ($file->isValid()) {
                    // Tentukan jenis file berdasarkan mime type
                    if (str_starts_with($file->getMimeType(), 'image/')) {
                        $persil->uploadFotoBidang($file, 'Foto - ' . ($index + 1));
                    } else {
                        $persil->uploadKoordinatFile($file, 'Dokumen - ' . ($index + 1));
                    }
                    $uploadedFiles++;
                }
            }
        }

        // Tambahkan pesan sukses dengan info jumlah file yang diupload
        $successMessage = 'Data persil berhasil ditambahkan';
        if ($uploadedFiles > 0) {
            $successMessage .= ' dengan ' . $uploadedFiles . ' file';
        }

        return redirect()->route('persil.index')
            ->with('success', $successMessage);
    }

    public function show($id)
    {
        $persil = Persil::with(['pemilik', 'dokumen', 'media'])->findOrFail($id);

        // Ambil semua file media untuk persil ini
        $mediaFiles = Media::where('ref_table', 'persil')
                          ->where('ref_id', $id)
                          ->orderBy('sort_order')
                          ->get();

        // Hitung statistik file
        $totalFiles = $mediaFiles->count();
        $imageFiles = $mediaFiles->whereIn('mime_type', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])->count();
        $pdfFiles = $mediaFiles->where('mime_type', 'application/pdf')->count();
        $docFiles = $mediaFiles->whereIn('mime_type', ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])->count();
        $excelFiles = $mediaFiles->whereIn('mime_type', ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->count();

        return view('pages.persil.show', compact(
            'persil',
            'mediaFiles',
            'totalFiles',
            'imageFiles',
            'pdfFiles',
            'docFiles',
            'excelFiles'
        ));
    }

    public function edit($id)
    {
        $persil = Persil::findOrFail($id);
        $wargaOptions = Warga::orderBy('nama')->get();
        $jenisPenggunaanOptions = JenisPenggunaan::orderBy('nama_penggunaan')->get();

        // Ambil file media untuk persil ini
        $mediaFiles = Media::where('ref_table', 'persil')
                          ->where('ref_id', $id)
                          ->orderBy('sort_order')
                          ->get();

        return view('pages.persil.edit', compact('persil', 'wargaOptions', 'jenisPenggunaanOptions', 'mediaFiles'));
    }

    public function update(Request $request, $id)
    {
        $persil = Persil::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_persil' => 'required|max:50|unique:persil,kode_persil,' . $persil->persil_id . ',persil_id',
            'pemilik_warga_id' => 'required|exists:warga,warga_id',
            'luas_m2' => 'required|numeric|min:0',
            'penggunaan' => 'required|max:100',
            'alamat_lahan' => 'required',
            'rt' => 'required|max:3',
            'rw' => 'required|max:3',
            'file_uploads.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        // Update data persil
        $persil->update($request->except(['file_uploads', 'delete_media']));

        // ===== UPLOAD FILE BARU MENGGUNAKAN METODE DARI MODEL =====
        $uploadedFiles = 0;
        if ($request->hasFile('file_uploads')) {
            foreach ($request->file('file_uploads') as $index => $file) {
                if ($file->isValid()) {
                    // Tentukan jenis file berdasarkan mime type
                    if (str_starts_with($file->getMimeType(), 'image/')) {
                        $persil->uploadFotoBidang($file, 'Foto - ' . ($index + 1));
                    } else {
                        $persil->uploadKoordinatFile($file, 'Dokumen - ' . ($index + 1));
                    }
                    $uploadedFiles++;
                }
            }
        }

        // ===== HAPUS FILE YANG DIPILIH =====
        $deletedFiles = 0;
        if ($request->has('delete_media')) {
            foreach ($request->delete_media as $mediaId) {
                $media = Media::find($mediaId);
                if ($media) {
                    // Hapus file dari storage
                    Storage::disk('public')->delete($media->file_url);
                    // Hapus dari database
                    $media->delete();
                    $deletedFiles++;
                }
            }
        }

        // Update success message
        $successMessage = 'Data persil berhasil diperbarui';
        $changes = [];

        if ($uploadedFiles > 0) {
            $changes[] = $uploadedFiles . ' file ditambahkan';
        }
        if ($deletedFiles > 0) {
            $changes[] = $deletedFiles . ' file dihapus';
        }

        if (!empty($changes)) {
            $successMessage .= ' (' . implode(', ', $changes) . ')';
        }

        return redirect()->route('persil.index')
            ->with('success', $successMessage);
    }

    public function destroy($id)
    {
        $persil = Persil::findOrFail($id);

        // Hapus semua file media terkait
        $mediaFiles = Media::where('ref_table', 'persil')
                          ->where('ref_id', $id)
                          ->get();

        $deletedFiles = 0;
        foreach ($mediaFiles as $media) {
            Storage::disk('public')->delete($media->file_url);
            $media->delete();
            $deletedFiles++;
        }

        // Hapus data persil
        $persil->delete();

        $successMessage = 'Data persil berhasil dihapus';
        if ($deletedFiles > 0) {
            $successMessage .= ' beserta ' . $deletedFiles . ' file';
        }

        return redirect()->route('persil.index')
            ->with('success', $successMessage);
    }

    // ===== METHOD DOWNLOAD FILE =====
    public function downloadFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);

        // Pastikan file milik persil
        if ($media->ref_table !== 'persil') {
            abort(403, 'File tidak dapat diakses');
        }

        // Pastikan file ada
        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File tidak ditemukan');
        }

        // Dapatkan nama file asli
        $originalName = pathinfo($media->file_url, PATHINFO_BASENAME);

        return Storage::disk('public')->download($media->file_url, $originalName);
    }

    // ===== METHOD PREVIEW FILE =====
    public function previewFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);

        // Pastikan file milik persil
        if ($media->ref_table !== 'persil') {
            abort(403, 'File tidak dapat diakses');
        }

        // Pastikan file ada
        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File tidak ditemukan');
        }

        // Jika file adalah gambar, tampilkan preview
        if (in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])) {
            return response()->file(storage_path('app/public/' . $media->file_url));
        }

        // Jika file PDF, redirect ke view khusus
        if ($media->mime_type === 'application/pdf') {
            return view('pages.persil.preview-pdf', compact('media'));
        }

        // Untuk file lainnya, force download
        return $this->downloadFile($mediaId);
    }
}
