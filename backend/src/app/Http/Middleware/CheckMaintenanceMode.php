<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maintenance = DB::table('settings')->where('key', 'maintenance_mode')->value('value');

        if ($maintenance === '1') {
            if (Auth::check() && Auth::user()->role === 'admin') {
                return $next($request);
            }
        
            return response()->json([
                'error' => 'Maintenance Mode',
                'message' => 'Nexus está realizando tareas de mantenimiento. Estaremos de vuelta pronto.'
            ], 503);
        }
        return $next($request);
    }
}
