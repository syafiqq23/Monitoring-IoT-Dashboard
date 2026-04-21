<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Control;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'title' => 'Dashboard',
            'controls' => \App\Models\Monitoring::latest()->paginate(5),
        ]);
    }
}
