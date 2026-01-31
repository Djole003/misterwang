<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // importuj model User
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Prikaz liste korisnika
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Metoda za update role korisnika (primer)
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|in:user,editor,admin', // validacija role, prilagodi po potrebi
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Uloga korisnika je ažurirana.');
    }
}
