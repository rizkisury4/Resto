<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password) || ! $user->is_admin) {
            return back()->withErrors(['email' => 'Invalid admin credentials'])->withInput();
        }

        session()->put('admin_id', $user->id);

        return redirect()->route('admin.orders.index');
    }

    public function logout()
    {
        session()->forget('admin_id');

        return redirect()->route('admin.login');
    }
}
