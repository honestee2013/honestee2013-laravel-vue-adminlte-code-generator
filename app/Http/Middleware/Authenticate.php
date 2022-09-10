<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Route;
use Redirect;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        if (! $request->expectsJson()) {
            //return Redirect::route("http://school1.localhost:8000");

            return route('login');
        }
    }
}