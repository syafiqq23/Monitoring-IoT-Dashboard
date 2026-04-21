<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardHistoryController extends Controller
{
    public function index()
    {
        $controls = Monitoring::latest();

        if(request('filter')) {
            $controls->where('created_at', 'like', '%' . request('filter') . '%');
        } else {
            $controls->where('created_at', 'like', '%' . Carbon::now()->format('Y-m-d') . '%');
        }

        return view('dashboard.histories.index', [
            'title'    => 'Dashboard | Histories',
            'today'    => Carbon::now()->format('Y-m-d'),
            'controls' => $controls->get(),
        ]);
    }

    public function cetak()
    {
        $controls = Monitoring::latest();

        if(request('filter')) {
            $controls->where('created_at', 'like', '%' . request('filter') . '%');
        } else {
            $controls->where('created_at', 'like', '%' . Carbon::now()->format('Y-m-d') . '%');
        }

        return view('dashboard.histories.cetakhistory', [
            'title'    => 'Dashboard | Histories',
            'today'    => Carbon::now()->format('Y-m-d'),
            'controls' => $controls->get(),
        ]);
    }

    public function destroy(Monitoring $control)
    {
        $date = $control->created_at->format('Y-m-d');
        Monitoring::destroy($control->id);
        return redirect('/dashboard/controls?filter=' . $date)->with('success', 'Data berhasil dihapus!');
    }
}