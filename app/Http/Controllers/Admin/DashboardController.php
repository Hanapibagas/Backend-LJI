<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $datas = Absen::with(['User'])->orderBy('id', 'DESC')->paginate(5);
        return view('landing.dashboard', compact('datas'));
    }
}
