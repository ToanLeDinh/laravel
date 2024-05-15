<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMaintain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(file_exists($maintenance = __DIR__.'/../../storage/framework/maintenance.php')){
            return response()->json(['message' => 'Hệ thống đang bảo trì. Vui lòng thử lại sau.'], 503);
        }
        return $next($request);
    }
}
