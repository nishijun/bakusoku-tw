<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckContentTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    // 他ユーザによるなりすましアクセス防止
    public function handle($request, Closure $next)
    {
      $id = $request->route()->parameter('id');
      if (Auth::user()->twitter_id !== $id) {
        return redirect()->route($request->route()->action['as'], ['id' => Auth::user()->twitter_id]);
      }
      return $next($request);
    }
}
