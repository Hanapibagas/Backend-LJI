<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CrewController extends Controller
{
    public function index()
    {
        $datas = User::all();
        return view('landing.crew.index', compact('datas'));
    }

    public function store(Request $request)
    {
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt('12345678'),
        ]);

        return redirect()->route('index_crew');
    }

    public function cari(Request $request)
    {
        $keywords = $request->search;
        $datas = User::where('name', 'like', "%" . $keywords . "%")->paginate(5);
        return view('landing.crew.index', compact('datas'));
    }
}
