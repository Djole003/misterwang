<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $narudzbinePoDanu = DB::table('narudzbine')
            ->select(DB::raw('DATE(datum_narucivanja) as datum'), DB::raw('COUNT(*) as broj'))
            ->groupBy('datum')
            ->orderBy('datum', 'asc')
            ->get();

        return view('admin.dashboard', compact('narudzbinePoDanu'));
    }
}
