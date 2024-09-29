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

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')
    ->middleware('auth')
    ->name('dashboard.')
    ->group(
        function () {
            Route::get('/', 'DashboardController@index')->name('index');
            Route::post('quota', 'DashboardController@quota')->name('quota');
            Route::post('penilaian', 'DashboardController@penilaian')->name('penilaian');
            Route::post('resign', 'DashboardController@resign')->name('resign');
            Route::post('mutasi', 'DashboardController@mutasi')->name('mutasi');
        }
    );
