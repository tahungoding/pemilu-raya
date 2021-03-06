<?php

namespace App\Http\Middleware;

use Alert;
use Closure;
use Illuminate\Http\Request;

class CheckActiveElection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (getActiveElection() === null) {
            Alert::info('Info', "Buat pemilu baru terlebih dahulu!");
            return redirect(route('elections.index'));
        }

        return $next($request);
    }
}
