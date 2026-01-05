<?php

namespace App\Http\Controllers;

use App\Models\SengketaPersil;
use App\Models\Persil;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
            'pihak_1' => 'required|max:100',
            'pihak_2' => 'required|max:100',
            'kronologi' => 'required',
            'status' => 'required|in:proses,selesai,dibatalkan',
            'penyelesaian' => 'nullable',
            'bukti_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        DB::beginTransaction();
        try {
            $sengketa = SengketaPersil::create([
                'persil_id' => $persil_id,
                'pihak_1' => $request->pihak_1,
                'pihak_2' => $request->pihak_2,
                'kronologi' => $request->kronologi,
                'status' => $request->status,
                'penyelesaian' => $request->penyelesaian,
            ]);

            // Upload Bukti
            if ($request->hasFile('bukti_files')) {
                foreach ($request->file('bukti_files') as $index => $file) {
                    if ($file->isValid()) {
                        $sengketa->uploadBuktiSengketa($file, 'Bukti Sengketa - ' . ($index + 1));
                    }
                }
            }

            DB::commit();
            return redirect()->route('sengketa-persil.index')
                ->with('success', 'Data sengketa berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
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
            'pihak_1' => 'required|max:100',
            'pihak_2' => 'required|max:100',
            'kronologi' => 'required',
            'status' => 'required|in:proses,selesai,dibatalkan',
            'penyelesaian' => 'nullable',
            'bukti_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal.');
        }

        DB::beginTransaction();
        try {
            $sengketa->update($request->except(['bukti_files', 'delete_media']));

            // Upload File Baru
            if ($request->hasFile('bukti_files')) {
                foreach ($request->file('bukti_files') as $file) {
                    if ($file->isValid()) {
                        $sengketa->uploadBuktiSengketa($file, 'Bukti Tambahan');
                    }
                }
            }

            // Hapus File Lama
            if ($request->has('delete_media')) {
                foreach ($request->delete_media as $mediaId) {
                    $media = Media::find($mediaId);
                    if ($media) {
                        Storage::disk('public')->delete($media->file_url);
                        $media->delete();
                    }
                }
            }

            DB::commit();
            return redirect()->route('sengketa-persil.index')->with('success', 'Data sengketa berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $sengketa = SengketaPersil::findOrFail($id);

        $mediaFiles = Media::where('ref_table', 'sengketa_persil')->where('ref_id', $id)->get();
        foreach ($mediaFiles as $media) {
            Storage::disk('public')->delete($media->file_url);
            $media->delete();
        }

        $sengketa->delete();
        return redirect()->route('sengketa-persil.index')->with('success', 'Data sengketa berhasil dihapus.');
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
