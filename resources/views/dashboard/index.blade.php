@extends('dashboard.layouts.main')

@section('container')

{{-- ALERT SECTION --}}
<div class="container-fluid pt-4 px-4" id="alert-section"></div>

{{-- CARD REALTIME --}}
<div class="container-fluid px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa-solid fa-temperature-low fa-2xl text-warning"></i>
                <div class="ms-3">
                    <p class="mb-2">Temperature</p>
                    <h6 class="mb-0"><span id="suhu">0</span> °C</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa-solid fa-hand-holding-droplet fa-2xl text-warning"></i>
                <div class="ms-3">
                    <p class="mb-2">Humidity</p>
                    <h6 class="mb-0"><span id="humidity">0</span> %</h6>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK REALTIME --}}
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded p-4">
                <h6 class="mb-3">Grafik Temperature (Realtime)</h6>
                <canvas id="chartSuhu"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded p-4">
                <h6 class="mb-3">Grafik Humidity (Realtime)</h6>
                <canvas id="chartHumidity"></canvas>
            </div>
        </div> 
    </div>
</div>

{{-- STATISTIK AVERAGE --}}
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-4">
        <h6 class="mb-3">Statistik Data</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <input type="date" class="form-control" id="stat-start" value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control" id="stat-end" value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <button class="btn btn-warning w-100" onclick="loadStatistik()">
                    <i class="bx bx-stats"></i> Tampilkan Statistik
                </button>
            </div>
        </div>
        <div class="row g-3" id="stat-result">
            <div class="col-md-2 col-sm-6">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Avg Temp</small>
                    <h5 class="mb-0 text-warning" id="avg-temp">-</h5>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Min Temp</small>
                    <h5 class="mb-0 text-info" id="min-temp">-</h5>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Max Temp</small>
                    <h5 class="mb-0 text-danger" id="max-temp">-</h5>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Avg Humidity</small>
                    <h5 class="mb-0 text-warning" id="avg-hum">-</h5>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Min Humidity</small>
                    <h5 class="mb-0 text-info" id="min-hum">-</h5>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Max Humidity</small>
                    <h5 class="mb-0 text-danger" id="max-hum">-</h5>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL RIWAYAT --}}
<div class="container-fluid pt-4 px-4 pb-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Riwayat Monitoring Terbaru</h6>
            <a href="/dashboard/controls" class="text-warning">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0" id="tabel-riwayat">
                <thead>
                    <tr class="text-dark">
                        <th>Tanggal</th>
                        <th>Pukul</th>
                        <th>Device ID</th>
                        <th>Temperature</th>
                        <th>Humidity</th>
                    </tr>
                </thead>
                <tbody id="tbody-riwayat">
                    @foreach ($controls as $control)
                    <tr>
                        <td>{{ $control->created_at->format('d M Y') }}</td>
                        <td>{{ $control->created_at->format('H:i') }}</td>
                        <td>{{ $control->device_id }}</td>
                        <td>{{ $control->temperature }} °C</td>
                        <td>{{ $control->humidity }} %</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('dashboard.realtime')
@endsection