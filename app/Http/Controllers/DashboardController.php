<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Persil;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Middleware sudah menangani pengecekan login
        // Cukup ambil data untuk dashboard

        $totalWarga = Warga::count();
        $totalPersil = Persil::count();
        $totalLuas = Persil::sum('luas_m2');

        // Hitung jenis kelamin
        $lakiLaki = Warga::where('jenis_kelamin', 'L')->count();
        $perempuan = Warga::where('jenis_kelamin', 'P')->count();

        // Ambil warga terbaru
        $wargaTerbaru = Warga::orderBy('created_at', 'desc')->limit(5)->get();

        // Ambil persil terbaru
        $persilTerbaru = Persil::with('pemilik')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('pages.dashboard', compact(
            'totalWarga',
            'totalPersil',
            'totalLuas',
            'lakiLaki',
            'perempuan',
            'wargaTerbaru',
            'persilTerbaru'
        ));
    }
}
