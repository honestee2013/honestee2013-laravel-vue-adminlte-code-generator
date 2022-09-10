<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
//use Spatie\Multitenancy\Exceptions\NoCurrentTenant;
use Illuminate\Contracts\Container\BindingResolutionException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Throws Illuminate\Contracts\Container\BindingResolutionException;
        // To avoid wrong subdomain tenant database table fatal errors
        $this->reportable(function (BindingResolutionException $e) {
            exit('<br /><br />
                <div style="color:red; font-weight: bold; font-size:1.2em">ERROR!</div><hr />
                <div><h3>Please, check your school address. IT SEEMS WRONG!</h3> <p> Exception: NoCurrentTenant </p> </div>
            ');
        });
    }
}
