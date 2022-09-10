<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//use Redirect;
//use Request;
use  Spatie\Multitenancy\Landlord;
//use DB;



use Spatie\Multitenancy\Models\Tenant;
//use Option;

//DB::table('options')->get();
//exit(json_encode(  App\Models\Honestee\VueCodeGen\Option::all() ) );
        // Throws Illuminate\Contracts\Container\BindingResolutionException;
        // To avoid wrong subdomain tenant database table fatal errors
        //app('currentTenant') ; 

       //exit( json_encode(Tenant::orderBy('created_at', 'desc')->first() ) );



    if(!Tenant::current())      
        abort("404");

    // routes
    Route::get('/', function () {
        return view('welcome');
    });
    

    Auth::routes(['verify' => true]);



    Route::middleware('tenant')->group(function() {

        Route::get('/register', function () {
            $domain = Tenant::current()->domain;
            $isSubdomain = strrpos($domain, '.', 5 ); // Skip 5 char from the back ie .com
            if(!$isSubdomain) // Main domain
                return redirect(route('school-owner-registration'));
            else
                return view('auth/register');

        })->name('register');

        Route::get('/free-school-website-registration', function () {
            $domain = Tenant::current()->domain;
            $isSubdomain = strrpos($domain, '.', 5 ); // Skip 5 char from the back ie .com
            if(!$isSubdomain) // Main domain
                return view('auth/school-owner-registration');
            else
                abort(404);

        })->name('school-owner-registration');

        Route::get('/school-staff-registration', function () {
            //exit('Second subdomain landing page '. Tenant::current()->domain);
            return view('auth/school-staff-registration');
        })->name('school-staff-registration');

        Route::get('/school-parents-registration', function () {
            //exit('Second subdomain landing page '. Tenant::current()->domain);
            return view('auth/school-parents-registration');
        })->name('school-parents-registration');

        Route::any('/school-students-registration', function () {
            //exit('Second subdomain landing page '. Tenant::current()->domain);
            return view('auth/school-students-registration');
        })->name('school-students-registration');      
    
           

        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        
        Route::get('home', function () {
            return redirect('/dashboard');
        });
        
        Route::get('/{vue_capture?}', function () {
            return view('home');
        })->where('vue_capture', '[\/\w\.-]*')->middleware('auth');
    });
