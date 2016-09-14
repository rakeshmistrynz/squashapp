<?php namespace App\Http\Middleware;

use Closure;

class RedirectifNotAdministrator
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!in_array(\Auth::user()->user_type,config('squash.club+member'))) {
            return redirect('notifications/club-notices');
        }
        return $next($request);
    }

}
