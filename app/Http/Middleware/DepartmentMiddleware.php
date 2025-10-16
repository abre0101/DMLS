<?php
// app/Http/Middleware/DepartmentMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentMiddleware
{
    public function handle(Request $request, Closure $next, ...$departments)
    {
        if (!Auth::check() || !in_array(Auth::user()->department->name, $departments)) {
            return redirect('/'); // Redirect if not authorized
        }

        return $next($request);
    }
}