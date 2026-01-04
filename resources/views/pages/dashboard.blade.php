@extends('layouts.admin.app')

@section('title', 'Dashboard - Bina Desa')
@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalWarga }}</h3>
                            <p>Total Warga</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('warga.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalPersil }}</h3>
                            <p>Total Persil</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <a href="{{ route('persil.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($totalLuas, 2) }}</h3>
                            <p>Total Luas (m²)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ruler-combined"></i>
                        </div>
                        <a href="{{ route('persil.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $lakiLaki }}/{{ $perempuan }}</h3>
                            <p>Laki / Perempuan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-venus-mars"></i>
                        </div>
                        <a href="{{ route('warga.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7">
                    <!-- Statistik Penggunaan Lahan -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
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
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Penggunaan Lahan</th>
                                            <th class="text-center">Jumlah Persil</th>
                                            <th class="text-center">Total Luas (m²)</th>
                                            <th class="text-center">Rata-rata</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($penggunaanStats as $stat)
                                        <tr>
                                            <td>{{ $stat->penggunaan }}</td>
                                            <td class="text-center"><span class="badge bg-primary">{{ $stat->total }}</span></td>
                                            <td class="text-center">{{ number_format($stat->total_luas, 2) }}</td>
                                            <td class="text-center">{{ number_format($stat->total_luas / $stat->total, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data penggunaan lahan</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Warga Terbaru -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-1"></i>
                                Warga Terbaru
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('warga.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>NIK</th>
                                            <th>Agama / Pekerjaan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($wargaTerbaru as $warga)
                                        <tr>
                                            <td><a href="{{ route('warga.show', $warga->warga_id) }}">{{ $warga->nama }}</a></td>
                                            <td>{{ $warga->no_ktp }}</td>
                                            <td>{{ $warga->agama ?? '-' }}, {{ $warga->pekerjaan ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $warga->jenis_kelamin == 'L' ? 'primary' : 'success' }}">
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

                <!-- Right col -->
                <section class="col-lg-5">
                    <!-- Distribusi Jenis Kelamin -->
                    <div class="card bg-gradient-info">
                        <div class="card-header">
                            <h3 class="card-title text-white">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Distribusi Jenis Kelamin
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($lakiLaki > 0 || $perempuan > 0)
                            <div class="row">
                                <div class="col-6 text-center">
                                    <h1 class="display-4 text-white">{{ $lakiLaki }}</h1>
                                    <p class="text-white">Laki-laki</p>
                                </div>
                                <div class="col-6 text-center">
                                    <h1 class="display-4 text-white">{{ $perempuan }}</h1>
                                    <p class="text-white">Perempuan</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                @php
                                    $totalGender = $lakiLaki + $perempuan;
                                    $percentageL = $totalGender > 0 ? ($lakiLaki / $totalGender) * 100 : 0;
                                    $percentageP = $totalGender > 0 ? ($perempuan / $totalGender) * 100 : 0;
                                @endphp
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $percentageL }}%">
                                        {{ number_format($percentageL, 1) }}%
                                    </div>
                                    <div class="progress-bar bg-warning" style="width: {{ $percentageP }}%">
                                        {{ number_format($percentageP, 1) }}%
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-chart-pie fa-3x text-white-50 mb-3"></i>
                                <p class="text-white">Belum ada data warga</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Login -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user mr-1"></i>
                                Informasi Login
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Nama:</div>
                                <div class="col-8">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Email:</div>
                                <div class="col-8">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Role:</div>
                                <div class="col-8">
                                    <span class="badge bg-{{ Auth::user()->role == 'admin' ? 'success' : (Auth::user()->role == 'super_admin' ? 'danger' : 'primary') }}">
                                        {{ Auth::user()->role }}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-muted">Login Terakhir:</div>
                                <div class="col-8">
                                    {{ session('last_login') ? \Carbon\Carbon::parse(session('last_login'))->format('d/m/Y H:i') : 'Baru login' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Persil Terbaru -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-map-marked-alt mr-1"></i>
                                Persil Terbaru
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('persil.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Pemilik</th>
                                            <th>Luas</th>
                                            <th>RT/RW</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($persilTerbaru as $persil)
                                        <tr>
                                            <td><a href="{{ route('persil.show', $persil->persil_id) }}">{{ $persil->kode_persil }}</a></td>
                                            <td>{{ $persil->penilik->nama ?? '-' }}</td>
                                            <td>{{ number_format($persil->luas_m2, 2) }} m²</td>
                                            <td>{{ $persil->rt }}/{{ $persil->rw }}</td>
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
    </section>
</div>
@endsection
