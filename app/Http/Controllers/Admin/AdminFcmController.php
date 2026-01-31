<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminFcmController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        DB::table('admin_fcm_tokens')->updateOrInsert(
            ['token' => $request->token],
            [
                'user_id' => auth()->id(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['status' => 'saved']);
    }
}
