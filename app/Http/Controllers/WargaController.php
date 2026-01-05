<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Import storage

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $filterableColumns = ['jenis_kelamin', 'agama', 'pekerjaan'];
        $searchableColumns = ['no_ktp', 'nama', 'telp', 'email'];

        $warga = Warga::filter($request, $filterableColumns)
            ->search($request, $searchableColumns)
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $agamaOptions = Warga::distinct()->pluck('agama');
        $pekerjaanOptions = Warga::distinct()->pluck('pekerjaan');
        $jenisKelaminOptions = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan'
        ];

        return view('pages.warga.index', compact('warga', 'agamaOptions', 'pekerjaanOptions', 'jenisKelaminOptions'));
    }

    public function create()
    {
        return view('pages.warga.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_ktp' => 'required|unique:warga|max:20',
            'nama' => 'required|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|max:20',
            'pekerjaan' => 'required|max:50',
            'telp' => 'nullable|max:15',
            'email' => 'nullable|email|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        $warga = Warga::create($request->except('foto'));

        // Logic Upload Foto
        if ($request->hasFile('foto')) {
            $warga->uploadAvatar($request->file('foto'));
        }

        return redirect()->route('warga.index')
            ->with('success', 'Data warga berhasil ditambahkan');
    }

    public function edit($id)
    {
        $warga = Warga::with('avatar')->findOrFail($id); // Eager load avatar
        return view('pages.warga.edit', compact('warga'));
    }

    public function update(Request $request, $id)
    {
        $warga = Warga::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'no_ktp' => 'required|max:20|unique:warga,no_ktp,' . $warga->warga_id . ',warga_id',
            'nama' => 'required|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|max:20',
            'pekerjaan' => 'required|max:50',
            'telp' => 'nullable|max:15',
            'email' => 'nullable|email|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        $warga->update($request->except('foto'));

        // Logic Update Foto
        if ($request->hasFile('foto')) {
            $warga->uploadAvatar($request->file('foto'));
        }

        return redirect()->route('warga.index')
            ->with('success', 'Data warga berhasil diperbarui');
    }

    public function show($id)
    {
        $warga = Warga::with('avatar')->findOrFail($id);
        return view('pages.warga.show', compact('warga'));
    }

    public function destroy($id)
    {
        $warga = Warga::findOrFail($id);

        // Hapus foto dari storage sebelum hapus data warga
        if ($warga->avatar) {
            Storage::disk('public')->delete($warga->avatar->file_url);
            $warga->avatar->delete();
        }

        $warga->delete();

        return redirect()->route('warga.index')
            ->with('success', 'Data warga berhasil dihapus');
    }
}
