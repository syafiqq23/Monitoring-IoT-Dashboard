<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Cetak Data Monitoring | {{ request('filter') ?: $today }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Arial', sans-serif; padding: 20px; }
        h5 { font-weight: bold; }
        table { width: 100%; font-size: 13px; }
        th { background-color: #f8f9fa; }
        .btn-action { margin-bottom: 20px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    {{-- Tombol aksi - tidak ikut tercetak --}}
    <div class="no-print btn-action d-flex gap-2">
        <button onclick="window.print()" class="btn btn-warning">
            🖨️ Cetak / Save PDF
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            ✕ Tutup
        </button>
    </div>

    {{-- Header --}}
    <div class="text-center mb-3">
        <h5>Rekap Data Monitoring Ruang Server</h5>
        <p class="mb-0">Tanggal: <strong>{{ request('filter') ?: $today }}</strong></p>
        <p class="mb-0">Total Data: <strong>{{ $controls->count() }} record</strong></p>
        <p class="mb-0 text-muted" style="font-size:12px">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <hr>

    {{-- Tabel --}}
    <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-warning">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pukul</th>
                <th>Device ID</th>
                <th>Temperature</th>
                <th>Humidity</th>
                <th>Status Temp</th>
                <th>Status Humidity</th>
            </tr>
        </thead>
        @php
            $no = 1;
            $tempMinNormal = 18; $tempMaxNormal = 27;
            $tempMinWarn = 15;   $tempMaxWarn = 30;
            $humMinNormal = 45;  $humMaxNormal = 60;
            $humMinWarn = 40;    $humMaxWarn = 70;
        @endphp
        <tbody>
            @if ($controls->count() == 0)
            <tr>
                <td colspan="8" class="text-center">Belum ada data</td>
            </tr>
            @endif
            @foreach ($controls as $control)
            @php
                $temp = $control->temperature;
                $hum  = $control->humidity;

                // Status Temperature
                if ($temp < $tempMinWarn || $temp > $tempMaxWarn)
                    $statusTemp = ['label' => '🔴 Bahaya', 'class' => 'text-danger fw-bold'];
                elseif ($temp < $tempMinNormal || $temp > $tempMaxNormal)
                    $statusTemp = ['label' => '⚠️ Warning', 'class' => 'text-warning fw-bold'];
                else
                    $statusTemp = ['label' => '✅ Normal', 'class' => 'text-success'];

                // Status Humidity
                if ($hum < $humMinWarn || $hum > $humMaxWarn)
                    $statusHum = ['label' => '🔴 Bahaya', 'class' => 'text-danger fw-bold'];
                elseif ($hum < $humMinNormal || $hum > $humMaxNormal)
                    $statusHum = ['label' => '⚠️ Warning', 'class' => 'text-warning fw-bold'];
                else
                    $statusHum = ['label' => '✅ Normal', 'class' => 'text-success'];
            @endphp
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $control->created_at->format('d M Y') }}</td>
                <td>{{ $control->created_at->format('H:i') }}</td>
                <td>{{ $control->device_id }}</td>
                <td>{{ $temp }} °C</td>
                <td>{{ $hum }} %</td>
                <td class="{{ $statusTemp['class'] }}">{{ $statusTemp['label'] }}</td>
                <td class="{{ $statusHum['class'] }}">{{ $statusHum['label'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="text-end mt-2 no-print">
        <small class="text-muted">Range Normal: Temp 18°C-27°C | Humidity 45%-60%</small>
    </div>

</body>
</html>