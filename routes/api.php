<?php


use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('opendaylight')->group(function () {
    Route::get('save', 'OpenflowController@save');
    Route::get('nodes', 'OpenflowController@nodes');
    Route::get('openflows', 'OpenflowController@flows');
    Route::get('openflows/{flow}', 'OpenflowController@flow');
    Route::get('received', 'OpenflowController@received');
});
