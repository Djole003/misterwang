<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,editor,admin',
        ]);

        $user = User::findOrFail($id);

        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return redirect()->back()->with('error', 'Ne možete ukloniti admin privilegije sa svog naloga.');
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Uloga korisnika je ažurirana.');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Ne možete deaktivirati svoj nalog.');
        }

        $user->active = !$user->active;
        $user->save();

        return redirect()->back()->with('success', 'Status korisnika je ažuriran.');
    }
}
