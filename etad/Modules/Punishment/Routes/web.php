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

Route::prefix('pu')
    ->middleware('auth')
    ->group(
        function () {
            // Punishment
            Route::prefix('punishment')
                ->name('punishment.')
                ->group(
                    function () {
                        Route::post('form/grid', 'PunishmentController@grid')->name('form.grid');
                        Route::get('form/{id}/print', 'PunishmentController@print')->name('form.print');
                        Route::get('form/{id}/riwayat', 'PunishmentController@riwayat')->name('form.riwayat');
                        Route::post('form/{id}/approval', 'PunishmentController@approvalSave')->name('form.approvalSave');
                        Route::post('form/riwayat/grid/{tad}', 'PunishmentController@riwayatGrid')->name('form.riwayat.grid');
                        Route::get('form/riwayat/create/{tad}', 'PunishmentController@riwayatCreate')->name('form.riwayat.create');
                        Route::post('form/riwayat/create/{tad}', 'PunishmentController@riwayatStore')->name('form.riwayat.store');
                        Route::get('form/riwayat/{punishment}', 'PunishmentController@riwayatShow')->name('form.riwayat.show');
                        Route::get('form/{id}/tracking', 'PunishmentController@tracking')->name('form.tracking');
                        Route::get('form/{id}/print', 'PunishmentController@print')->name('form.print');
                        Route::resource('form', PunishmentController::class);
                    }
                );

            /* Reward */
            Route::prefix('reward')
                ->name('reward.')
                ->group(
                    function () {
                        Route::post('form/grid', 'RewardController@grid')->name('form.grid');
                        Route::get('form/{id}/print', 'RewardController@print')->name('form.print');
                        Route::get('form/{id}/riwayat', 'RewardController@riwayat')->name('form.riwayat');
                        Route::post('form/{id}/approval', 'RewardController@approvalSave')->name('form.approvalSave');
                        Route::post('form/riwayat/grid/{tad}', 'RewardController@rewardGrid')->name('form.riwayat.grid');
                        Route::get('form/riwayat/create/{tad}', 'RewardController@riwayatCreate')->name('form.riwayat.create');
                        Route::post('form/riwayat/create/{tad}', 'RewardController@riwayatStore')->name('form.riwayat.store');
                        Route::get('form/riwayat/{reward}', 'RewardController@riwayatShow')->name('form.riwayat.show');
                        Route::get('form/{id}/tracking', 'RewardController@tracking')->name('form.tracking');
                        Route::get('form/{id}/print', 'RewardController@print')->name('form.print');
                        Route::resource('form', RewardController::class);
                    }
                );

            // Pembinaan
            Route::prefix('pembinaan')
                ->name('pembinaan.')
                ->group(
                    function () {
                        Route::post('form/grid', 'PembinaanController@grid')->name('form.grid');
                        Route::get('form/{id}/print', 'PembinaanController@print')->name('form.print');
                        Route::get('form/{id}/riwayat', 'PembinaanController@riwayat')->name('form.riwayat');
                        Route::post('form/{id}/approval', 'PembinaanController@approvalSave')->name('form.approvalSave');
                        Route::post('form/riwayat/grid/{tad}', 'PembinaanController@riwayatGrid')->name('form.riwayat.grid');
                        Route::get('form/riwayat/create/{tad}', 'PembinaanController@riwayatCreate')->name('form.riwayat.create');
                        Route::post('form/riwayat/create/{tad}', 'PembinaanController@riwayatStore')->name('form.riwayat.store');
                        Route::get('form/riwayat/{pembinaan}', 'PembinaanController@riwayatShow')->name('form.riwayat.show');
                        Route::get('form/{id}/tracking', 'PembinaanController@tracking')->name('form.tracking');
                        Route::get('form/{id}/print', 'PembinaanController@print')->name('form.print');
                        Route::resource('form', PembinaanController::class);
                    }
                );
        }
    );
