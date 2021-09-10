<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckReviewer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('reviewer')) {
            $user = Auth()->guard('reviewer')->user();
            if ($user->status  && $user->ev  && $user->sv  && $user->tv) {
                return $next($request);
            } else {
                return redirect()->route('reviewer.authorization');
            }
        }
        abort(403);
    }
}
