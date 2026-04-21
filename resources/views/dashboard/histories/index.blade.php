@extends('dashboard.layouts.main')

@section('container')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Riwayat Monitoring</h6>
        </div>
        <div class="input-group mb-3">
            <form action="/dashboard/controls" class="d-flex w-100">
                @csrf
                <input type="date" class="form-control" name="filter" value="{{ request('filter') ?: $today }}">
                <button class="btn btn-warning mx-2" type="submit"><i class="bx bx-search"></i> Filter</button>
            </form>
            <a class="btn btn-secondary" target="_blank" href="/dashboard/cetak{{ request()->has('filter') ? '?filter=' . request('filter') : '' }}">
                <i class="bx bx-printer"></i> Cetak
            </a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pukul</th>
                        <th>Device ID</th>
                        <th>Temperature</th>
                        <th>Humidity</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                @php $no = 1; @endphp
                <tbody>
                    @if ($controls->count() == 0)
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data</td>
                    </tr>
                    @endif
                    @foreach ($controls as $control)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $control->created_at->format('d M Y') }}</td>
                        <td>{{ $control->created_at->format('H:i') }}</td>
                        <td>{{ $control->device_id }}</td>
                        <td>{{ $control->temperature }} °C</td>
                        <td>{{ $control->humidity }} %</td>
                        <td>
                            <form action="/dashboard/controls/{{ $control->id }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection