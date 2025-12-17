<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            // Not logged in
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Assuming your User model has a 'role' attribute or method
        if ($user->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
