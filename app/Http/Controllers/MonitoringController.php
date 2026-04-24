<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;

class MonitoringController extends Controller
{
    public function bacasuhu()
    {
        $data = Monitoring::latest()->first();
        return $data ? $data->temperature : 0;
    }

    public function bacahumidity()
    {
        $data = Monitoring::latest()->first();
        return $data ? $data->humidity : 0;
    }

        public function statistik()
    {
        $start = request('start', now()->format('Y-m-d'));
        $end   = request('end',   now()->format('Y-m-d'));

        $data = Monitoring::whereBetween('created_at', [
            $start . ' 00:00:00',
            $end   . ' 23:59:59',
        ]);

        return response()->json([
            'avg_temp' => round($data->avg('temperature'), 1),
            'min_temp' => $data->min('temperature'),
            'max_temp' => $data->max('temperature'),
            'avg_hum'  => round((clone $data)->avg('humidity'), 1),
            'min_hum'  => (clone $data)->min('humidity'),
            'max_hum'  => (clone $data)->max('humidity'),
        ]);
    }
    
    public function tabelRiwayat()
    {
        $data = Monitoring::latest()->take(10)->get();
        $html = '';
        foreach ($data as $row) {
            $html .= "<tr>
                <td>{$row->created_at->format('d M Y')}</td>
                <td>{$row->created_at->format('H:i')}</td>
                <td>{$row->device_id}</td>
                <td>{$row->temperature} °C</td>
                <td>{$row->humidity} %</td>
            </tr>";
        }
        return $html;
    }

    // public function chartHariIni()
    // {
    //     $data = Monitoring::whereDate('created_at', today())
    //         ->orderBy('created_at')
    //         ->get();

    //     return response()->json([
    //         'labels' => $data->map(fn($d) => $d->created_at->format('H:i')),
    //         'temp'   => $data->pluck('temperature'),
    //         'hum'    => $data->pluck('humidity'),
    //     ]);
    // }

        public function chartHariIni()
    {
        $tanggal = '2026-04-16'; // ← hardcode sementara untuk test

        $data = Monitoring::whereDate('created_at', $tanggal)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'labels' => $data->map(fn($d) => $d->created_at->format('H:i')),
            'temp'   => $data->pluck('temperature'),
            'hum'    => $data->pluck('humidity'),
        ]);
    }
}