@extends('layouts.admin.app')
@section('content')
    {{-- Style khusus untuk halaman Dashboard --}}
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --warning: #f72585;
            --info: #7209b7;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border-radius: 12px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        /* Stat Cards Grid */
        .small-box {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px 20px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .bg-info {
            border-top: 4px solid var(--primary);
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%) !important;
        }

        .bg-success {
            border-top: 4px solid #2ecc71;
            background: linear-gradient(135deg, #ffffff 0%, #f0fff4 100%) !important;
        }

        .bg-warning {
            border-top: 4px solid #f39c12;
            background: linear-gradient(135deg, #ffffff 0%, #fff9f0 100%) !important;
        }

        .bg-danger {
            border-top: 4px solid #e74c3c;
            background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%) !important;
        }

        .small-box .inner { margin-bottom: 15px; }
        .small-box h3 { font-size: 2.2rem; font-weight: 700; margin: 0 0 5px 0; color: var(--dark); }
        .small-box p { font-size: 0.95rem; color: var(--gray); margin: 0; }

        .small-box .icon {
            position: absolute;
            top: 20px; right: 20px;
            font-size: 2.5rem;
            color: rgba(67, 97, 238, 0.2);
            transition: var(--transition);
        }

        .small-box:hover .icon { transform: scale(1.1); color: rgba(67, 97, 238, 0.3); }

        .small-box-footer {
            display: inline-block;
            margin-top: 10px; padding: 5px 0;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500; font-size: 0.9rem;
            border-top: 1px solid var(--light-gray);
            width: 100%;
        }
        .small-box-footer:hover { color: var(--secondary); }

        /* Card Styles */
        .card {
            background: white;
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--box-shadow);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--light-gray);
            padding: 20px 25px;
            display: flex; justify-content: space-between; align-items: center;
        }

        .card-title { font-size: 1.2rem; font-weight: 600; color: var(--dark); margin: 0; display: flex; align-items: center; }
        .card-title i { margin-right: 10px; color: var(--primary); }
        .card-body { padding: 25px; }

        /* Helpers */
        .main-row { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }
        @media (max-width: 1024px) { .main-row { grid-template-columns: 1fr; } }

        .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }

        /* * FIX CARD GEPENG (Distribusi Gender)
         * ===================================
         */
        /* Update Gradient agar vertikal seperti foto */
        .bg-gradient-info {
            background: linear-gradient(180deg, #4361ee 0%, #3a0ca3 100%) !important;
            color: white;
        }
        .bg-gradient-info .card-header { border-bottom: 1px solid rgba(255,255,255,0.1); }
        .bg-gradient-info .card-title, .bg-gradient-info .card-title i { color: white !important; }

        /* Style Khusus Body Card Gender */
        .gender-card-body {
            min-height: 400px; /* MEMAKSA TINGGI AGAR TIDAK GEPENG */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Konten vertikal di tengah */
            padding: 40px 30px !important;
        }

        .gender-number { font-size: 3.5rem; font-weight: 700; margin-bottom: 5px; }
        .gender-label { font-size: 1rem; opacity: 0.9; font-weight: 300; }

        /* Progress Bar Custom */
        .custom-progress {
            height: 20px;
            border-radius: 50px;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.2);
            margin-top: 30px;
        }
    </style>



        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalWarga }}</h3>
                        <p>Total Warga</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('warga.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalPersil }}</h3>
                        <p>Total Persil</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <a href="{{ route('persil.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($totalLuas, 2) }}</h3>
                        <p>Total Luas (m²)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <a href="{{ route('persil.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $lakiLaki }}/{{ $perempuan }}</h3>
                        <p>Laki / Perempuan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-venus-mars"></i>
                    </div>
                    <a href="{{ route('warga.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="main-row">
            <section class="col-lg-90">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie"></i>
                            Statistik Penggunaan Lahan
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            $penggunaanStats = App\Models\Persil::select('penggunaan', \DB::raw('count(*) as total'), \DB::raw('sum(luas_m2) as total_luas'))
                                ->groupBy('penggunaan')
                                ->orderBy('total', 'desc')
                                ->get();
                        @endphp

                        @if($penggunaanStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover w-100">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="padding: 12px 16px;">Penggunaan Lahan</th>
                                        <th class="text-center">Jumlah Persil</th>
                                        <th class="text-center">Total Luas (m²)</th>
                                        <th class="text-center">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penggunaanStats as $stat)
                                    <tr style="border-bottom: 1px solid var(--light-gray);">
                                        <td style="padding: 16px;">{{ $stat->penggunaan }}</td>
                                        <td class="text-center" style="padding: 16px;">
                                            <span class="badge bg-primary">{{ $stat->total }}</span>
                                        </td>
                                        <td class="text-center" style="padding: 16px;">{{ number_format($stat->total_luas, 2) }}</td>
                                        <td class="text-center" style="padding: 16px;">{{ number_format($stat->total_luas / $stat->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                            <p class="text-muted">Belum ada data penggunaan lahan</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i>
                            Warga Terbaru
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('warga.create') }}" class="btn btn-sm btn-primary" style="padding: 6px 12px; border-radius: 6px; text-decoration: none; color: white; background-color: var(--primary);">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="padding: 12px 16px;">Nama</th>
                                        <th>NIK</th>
                                        <th>Agama / Pekerjaan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($wargaTerbaru as $warga)
                                    <tr style="border-bottom: 1px solid var(--light-gray);">
                                        <td style="padding: 16px;">
                                            <a href="{{ route('warga.show', $warga->warga_id) }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">{{ $warga->nama }}</a>
                                        </td>
                                        <td style="padding: 16px;">{{ $warga->no_ktp }}</td>
                                        <td style="padding: 16px;">{{ $warga->agama ?? '-' }}, {{ $warga->pekerjaan ?? '-' }}</td>
                                        <td style="padding: 16px;">
                                            <span class="badge bg-{{ $warga->jenis_kelamin == 'L' ? 'primary' : 'success' }}" style="color:white; background-color: {{ $warga->jenis_kelamin == 'L' ? '#4361ee' : '#2ecc71' }};">
                                                {{ $warga->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-user-slash me-2"></i>Belum ada data warga
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-lg-100">
                <div class="card bg-gradient-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie me-2"></i>
                            Distribusi Jenis Kelamin
                        </h3>
                    </div>
                    <div class="card-body gender-card-body">
                        @if($lakiLaki > 0 || $perempuan > 0)
                        <div class="row align-items-center mb-4">
                            <div class="col-6 text-center" style="border-right: 1px solid rgba(255,255,255,0.2);">
                                <div class="gender-number">{{ $lakiLaki }}</div>
                                <div class="gender-label">Laki-laki</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="gender-number">{{ $perempuan }}</div>
                                <div class="gender-label">Perempuan</div>
                            </div>
                        </div>

                        <div class="mt-2">
                            @php
                                $totalGender = $lakiLaki + $perempuan;
                                $percentageL = $totalGender > 0 ? ($lakiLaki / $totalGender) * 100 : 0;
                                $percentageP = $totalGender > 0 ? ($perempuan / $totalGender) * 100 : 0;
                            @endphp

                            <div class="d-flex justify-content-between mb-2 small font-weight-bold">
                                <span>{{ number_format($percentageL, 1) }}%</span>
                                <span>{{ number_format($percentageP, 1) }}%</span>
                            </div>

                            <div class="progress custom-progress">
                                <div class="progress-bar" style="width: {{ $percentageL }}%; background-color: #4361ee;" role="progressbar"></div>
                                <div class="progress-bar" style="width: {{ $percentageP }}%; background-color: #f72585;" role="progressbar"></div>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-pie fa-4x mb-3" style="opacity: 0.6;"></i>
                            <p class="h5 font-weight-light">Belum ada data warga</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i>
                            Informasi Login
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3" style="border-bottom: 1px solid var(--light-gray); padding-bottom: 10px;">
                            <div class="col-4 text-muted" style="color: var(--gray); font-weight: 500;">Nama:</div>
                            <div class="col-8">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="row mb-3" style="border-bottom: 1px solid var(--light-gray); padding-bottom: 10px;">
                            <div class="col-4 text-muted" style="color: var(--gray); font-weight: 500;">Email:</div>
                            <div class="col-8">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="row mb-3" style="border-bottom: 1px solid var(--light-gray); padding-bottom: 10px;">
                            <div class="col-4 text-muted" style="color: var(--gray); font-weight: 500;">Role:</div>
                            <div class="col-8">
                                <span class="badge" style="background-color: {{ Auth::user()->role == 'admin' ? '#2ecc71' : (Auth::user()->role == 'super_admin' ? '#e74c3c' : '#4361ee') }}; color: white;">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 text-muted" style="color: var(--gray); font-weight: 500;">Login Terakhir:</div>
                            <div class="col-8">
                                {{ session('last_login') ? \Carbon\Carbon::parse(session('last_login'))->format('d/m/Y H:i') : 'Baru login' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt"></i>
                            Persil Terbaru
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('persil.create') }}" class="btn btn-sm btn-primary" style="padding: 6px 12px; border-radius: 6px; text-decoration: none; color: white; background-color: var(--primary);">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="padding: 12px 16px;">Kode</th>
                                        <th>Pemilik</th>
                                        <th>Luas</th>
                                        <th>RT/RW</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($persilTerbaru as $persil)
                                    <tr style="border-bottom: 1px solid var(--light-gray);">
                                        <td style="padding: 16px;">
                                            <a href="{{ route('persil.show', $persil->persil_id) }}" style="color: var(--primary); text-decoration: none;">{{ $persil->kode_persil }}</a>
                                        </td>
                                        <td style="padding: 16px;">{{ $persil->penilik->nama ?? '-' }}</td>
                                        <td style="padding: 16px;">{{ number_format($persil->luas_m2, 2) }} m²</td>
                                        <td style="padding: 16px;">{{ $persil->rt }}/{{ $persil->rw }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-landmark me-2"></i>Belum ada data persil
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Scripts Animations --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi untuk stat cards
            document.querySelectorAll('.small-box').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection
