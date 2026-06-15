<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $adminId = session('admin_id');
        if (! $adminId) {
            return redirect()->route('admin.login');
        }

        $user = User::find($adminId);
        if (! $user || ! $user->is_admin) {
            session()->forget('admin_id');
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
