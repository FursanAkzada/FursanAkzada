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

Route::prefix('penilaian')
    ->name('penilaian.')
    ->middleware(['auth', 'notified'])
    ->group(
        function () {
            Route::resource('vendor', 'Vendor\PenilaianController');
            Route::prefix('vendor')
                ->name('vendor.')
                ->namespace('Vendor')
                ->group(
                    function () {
                        // Penilaian Vendor
                        Route::get('{id}/print', 'PenilaianController@print')->name('print');
                        Route::get('{id}/riwayat', 'PenilaianController@riwayat')->name('riwayat');
                        Route::post('{id}/approval', 'PenilaianController@approvalSave')->name('approvalSave');
                        Route::post('grid', 'PenilaianController@grid')->name('grid');
                        Route::get('{id}/tracking', 'PenilaianController@tracking')->name('tracking');

                        // Review Vendor
                        Route::post('review/grid', 'ReviewController@grid')->name('review.grid');
                        Route::post('review/review/{review}', 'ReviewController@review')->name('review.review');
                        Route::resource('review', ReviewController::class);
                    }
                );

            Route::resource('tad', 'Tad\PenilaianController');
            Route::prefix('tad')
                ->name('tad.')
                ->namespace('Tad')
                ->group(
                    function () {
                        Route::get('{id}/print', 'PenilaianController@print')->name('print');
                        Route::get('penilaian/{id}/riwayat', 'PenilaianController@riwayat')->name('riwayat');
                        Route::post('{id}/approval', 'PenilaianController@approvalSave')->name('approvalSave');
                        Route::post('grid', 'PenilaianController@grid')->name('grid');
                        Route::post('tad/grid/{tad}', 'PenilaianController@penilaianGrid')->name('tad.grid');
                        Route::get('tad/create/{tad}', 'PenilaianController@create')->name('tad.create');
                        Route::post('tad/create/{tad}', 'PenilaianController@store')->name('tad.store');
                        Route::get('tad/{penilaian}', 'PenilaianController@penilaianShow')->name('tad.show');
                        Route::get('tad/{penilaian}/edit', 'PenilaianController@edit')->name('tad.edit');
                        Route::put('tad/{penilaian}', 'PenilaianController@update')->name('tad.update');
                        Route::delete('tad/{penilaian}', 'PenilaianController@destroy')->name('tad.destroy');
                        Route::get('tad/{id}/tracking', 'PenilaianController@tracking')->name('tracking');
                    }
            );

            /* Kandidat */
            Route::get('perpanjangan/{id}/edit', 'Tad\PerpanjanganController@edit')->name('perpanjangan.edit');
            Route::get('perpanjangan/{id}/detailCreate', 'Tad\PerpanjanganController@detailCreate')->name('perpanjangan.detailCreate');
            Route::post('perpanjangan/{pengajuan}/detailStore', 'Tad\PerpanjanganController@detailStore')->name('perpanjangan.detailStore');
            Route::post('perpanjangan/grid', 'Tad\PerpanjanganController@grid')->name('perpanjangan.grid');
            Route::delete('perpanjangan/kandidatDestroy/{id}', 'Tad\PerpanjanganController@kandidatDestroy')->name('perpanjangan.kandidatDestroy');
            Route::post('perpanjangan/calonKandidatGrid/{pengajuan}', 'Tad\PerpanjanganController@calonKandidatGrid')->name('perpanjangan.calonperpanjangan.grid');
            Route::post('perpanjangan/personil/grid/{pengajuan}', 'Tad\PerpanjanganController@personilGrid')->name('perpanjangan.personil.grid');
            Route::post('perpanjangan/personil/gridShow/{pengajuan}', 'Tad\PerpanjanganController@personilGridShow')->name('perpanjangan.personil.grid_show');
            Route::post('perpanjangan/{id}/approval', 'Tad\PerpanjanganController@approvalSave')->name('perpanjangan.approvalSave');
            Route::get('perpanjangan/{id}/riwayat', 'Tad\PerpanjanganController@riwayat')->name('perpanjangan.riwayat');
            Route::get('perpanjangan/{id}/tracking', 'Tad\PerpanjanganController@tracking')->name('perpanjangan.tracking');
            Route::get('perpanjangan/{id}/approval', 'Tad\PerpanjanganController@approval')->name('perpanjangan.approval');
            Route::post('perpanjangan/{id}/approval-save', 'Tad\PerpanjanganController@approvalSave')->name('perpanjangan.approvalSave');
            Route::resource('perpanjangan', Tad\PerpanjanganController::class);
            Route::get('perpanjangan/{id}/print', 'Tad\PerpanjanganController@print')->name('perpanjangan.print');
        }
    );
