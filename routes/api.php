<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});

Route::namespace('App\\Http\\Controllers\\API\V1')->group(function () {
    Route::get('profile', 'ProfileController@profile');
    Route::put('profile', 'ProfileController@updateProfile');
    Route::post('change-password', 'ProfileController@changePassword');
    Route::get('tag/list', 'TagController@list');
    Route::get('category/list', 'CategoryController@list');
    Route::post('product/upload', 'ProductController@upload');

    Route::apiResources([
        'user' => 'UserController',
        'role' => 'RoleController',
        'product' => 'ProductController',
        'category' => 'CategoryController',
        'tag' => 'TagController',
    ]);
});



Route::apiResources([ 'schools' => 'App\Http\Controllers\Honestee\VueCodeGen\SchoolController' ]);
Route::apiResources([ 'sections' => 'App\Http\Controllers\Honestee\VueCodeGen\SectionController' ]);
Route::apiResources([ 'classrooms' => 'App\Http\Controllers\Honestee\VueCodeGen\ClassroomController' ]);
Route::apiResources([ 'subjects' => 'App\Http\Controllers\Honestee\VueCodeGen\SubjectController' ]);
Route::apiResources([ 'departments' => 'App\Http\Controllers\Honestee\VueCodeGen\DepartmentController' ]);
Route::apiResources([ 'assessments' => 'App\Http\Controllers\Honestee\VueCodeGen\AssessmentController' ]);
Route::apiResources([ 'parts' => 'App\Http\Controllers\Honestee\VueCodeGen\PartController' ]);
Route::apiResources([ 'codes' => 'App\Http\Controllers\Honestee\VueCodeGen\CodeController' ]);
Route::apiResources([ 'roles' => 'App\Http\Controllers\Honestee\VueCodeGen\RoleController' ]);
Route::apiResources([ 'permissions' => 'App\Http\Controllers\Honestee\VueCodeGen\PermissionController' ]);
Route::apiResources([ 'users' => 'App\Http\Controllers\Honestee\VueCodeGen\UserController' ]);
Route::apiResources([ 'options' => 'App\Http\Controllers\Honestee\VueCodeGen\OptionController' ]);