<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if(!auth()->check()){
            return redirect()->route('login');
        }
        $userRole = auth()->user()->role;

        if(in_array($userRole, $roles)){
        return $next($request);
        }
        // if($userRole == 'admin'){
        //     return redirect()->route('admin.index');
        // }
        // elseif($userRole == 'hr'){
        //     return redirect()->route('hr.index');
        // }
        // else{
        //     return redirect()->route('employee.index');

        // }
        abort(403, 'Unauthorized');
    }
}
