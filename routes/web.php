<?php

use App\Http\Controllers\SingleSignController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/roles', function () {
    return auth()->user()->getRoles();
});

Route::get('/portal-sso', [SingleSignController::class, 'login']);
Route::get('/sso/auth', [SingleSignController::class, 'auth']);
