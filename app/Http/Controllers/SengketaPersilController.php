<?php
// app/Http/Controllers/SengketaPersilController.php

namespace App\Http\Controllers;

use App\Models\SengketaPersil;
use App\Models\Persil;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SengketaPersilController extends Controller
{
    public function index(Request $request)
    {
        $filterableColumns = ['status'];
        $searchableColumns = ['pihak_1', 'pihak_2', 'kronologi', 'penyelesaian'];

        $sengketa = SengketaPersil::with(['persil.pemilik'])
            ->filter($request, $filterableColumns)
            ->search($request, $searchableColumns)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $statusOptions = [
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return view('pages.sengketa-persil.index', compact('sengketa', 'statusOptions'));
    }

    public function create($persil_id)
    {
        $persil = Persil::with('pemilik')->findOrFail($persil_id);
        return view('pages.sengketa-persil.create', compact('persil'));
    }

    public function store(Request $request, $persil_id)
    {
        $validator = Validator::make($request->all(), [
            'pihak_1' => 'required|max:200',
            'pihak_2' => 'required|max:200',
            'kronologi' => 'required',
            'status' => 'required|in:proses,selesai,dibatalkan',
            'penyelesaian' => 'nullable',
            'bukti_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ], [
            'pihak_1.required' => 'Pihak 1 harus diisi',
            'pihak_2.required' => 'Pihak 2 harus diisi',
            'kronologi.required' => 'Kronologi sengketa harus diisi',
            'status.required' => 'Status sengketa harus dipilih',
            'bukti_files.*.mimes' => 'Format file harus PDF, JPG, JPEG, PNG, DOC, atau DOCX',
            'bukti_files.*.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        // Cek apakah sudah ada sengketa aktif untuk persil ini
        $existingSengketa = SengketaPersil::where('persil_id', $persil_id)
            ->where('status', 'proses')
            ->first();

        if ($existingSengketa) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Masih ada sengketa yang berstatus proses untuk persil ini. Selesaikan terlebih dahulu.');
        }

        $sengketa = SengketaPersil::create([
            'persil_id' => $persil_id,
            'pihak_1' => $request->pihak_1,
            'pihak_2' => $request->pihak_2,
            'kronologi' => $request->kronologi,
            'status' => $request->status,
            'penyelesaian' => $request->penyelesaian,
        ]);

        // ===== UPLOAD FILE BUKTI MENGGUNAKAN METODE DARI MODEL =====
        if ($request->hasFile('bukti_files')) {
            foreach ($request->file('bukti_files') as $index => $file) {
                if ($file->isValid()) {
                    $sengketa->uploadBuktiSengketa($file, 'Bukti Sengketa - ' . ($index + 1));
                }
            }
        }

        return redirect()->route('sengketa-persil.index')
            ->with('success', 'Data sengketa persil berhasil ditambahkan');
    }

    public function show($id)
    {
        $sengketa = SengketaPersil::with(['persil.pemilik'])->findOrFail($id);
        $mediaFiles = Media::where('ref_table', 'sengketa_persil')
                          ->where('ref_id', $id)
                          ->orderBy('sort_order')
                          ->get();

        return view('pages.sengketa-persil.show', compact('sengketa', 'mediaFiles'));
    }

    public function edit($id)
    {
        $sengketa = SengketaPersil::with(['persil.pemilik'])->findOrFail($id);
        $mediaFiles = Media::where('ref_table', 'sengketa_persil')
                          ->where('ref_id', $id)
                          ->orderBy('sort_order')
                          ->get();

        return view('pages.sengketa-persil.edit', compact('sengketa', 'mediaFiles'));
    }

    public function update(Request $request, $id)
    {
        $sengketa = SengketaPersil::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pihak_1' => 'required|max:200',
            'pihak_2' => 'required|max:200',
            'kronologi' => 'required',
            'status' => 'required|in:proses,selesai,dibatalkan',
            'penyelesaian' => 'nullable',
            'bukti_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ], [
            'pihak_1.required' => 'Pihak 1 harus diisi',
            'pihak_2.required' => 'Pihak 2 harus diisi',
            'kronologi.required' => 'Kronologi sengketa harus diisi',
            'status.required' => 'Status sengketa harus dipilih',
            'bukti_files.*.mimes' => 'Format file harus PDF, JPG, JPEG, PNG, DOC, atau DOCX',
            'bukti_files.*.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        $sengketa->update([
            'pihak_1' => $request->pihak_1,
            'pihak_2' => $request->pihak_2,
            'kronologi' => $request->kronologi,
            'status' => $request->status,
            'penyelesaian' => $request->penyelesaian,
        ]);

        // ===== UPLOAD FILE BARU MENGGUNAKAN METODE DARI MODEL =====
        if ($request->hasFile('bukti_files')) {
            foreach ($request->file('bukti_files') as $index => $file) {
                if ($file->isValid()) {
                    $sengketa->uploadBuktiSengketa($file, 'Bukti Sengketa - Tambahan');
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

        return redirect()->route('sengketa-persil.index')
            ->with('success', 'Data sengketa persil berhasil diperbarui');
    }

    public function destroy($id)
    {
        $sengketa = SengketaPersil::findOrFail($id);

        // Hapus semua file media terkait
        $mediaFiles = Media::where('ref_table', 'sengketa_persil')
                          ->where('ref_id', $id)
                          ->get();

        foreach ($mediaFiles as $media) {
            Storage::disk('public')->delete($media->file_url);
            $media->delete();
        }

        $sengketa->delete();

        return redirect()->route('sengketa-persil.index')
            ->with('success', 'Data sengketa persil berhasil dihapus');
    }

    // Method untuk download file bukti
    public function downloadFile($mediaId)
    {
        $media = Media::findOrFail($mediaId);

        if ($media->ref_table !== 'sengketa_persil') {
            abort(403, 'File tidak dapat diakses');
        }

        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($media->file_url);
    }
}
