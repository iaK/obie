<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
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
        $bot = resolve("botman");
        $user = $bot->getUser();

        if(!$user) {
            abort(404);
        }

        $id = $user->getId();

        if(!$id) {
            abort(404);
        }

        $user = User::whereFacebookId($id)->first();

        if(!$user) {
            abort(404);
        }

        Auth::login($user);

        return $next($message);
        return $next($request);
    }
}
