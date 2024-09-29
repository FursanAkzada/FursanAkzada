<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ReadNotification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($auth = Auth::guard($guard)->user()) {
            $uri    = url($request->getRequestUri());
            $notifs = $auth->unreadNotifications;
            $notifs = $notifs->filter(function ($value, $key) use ($uri) {
                return ($value->data['link'] ?? '') == $uri;
            });

            if($notif = $notifs->first()){
                $notif->markAsRead();
            }
        }

        return $next($request);
    }
}
