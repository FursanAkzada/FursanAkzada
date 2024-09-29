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

Route::prefix('master')
    ->middleware('auth')
    ->name('master.')
    ->group(
        function () {
            Route::prefix('so')
                ->name('so.')
                ->namespace('SO')
                ->group(
                    function () {
                        Route::get('jabatan-tad/ajax', 'DireksiController@cekLevelTipeStruktur')->name('cek-level-tipe-struktur');
                        Route::post('root/grid', 'RootController@grid')->name('root.grid');
                        Route::post('root/city', 'RootController@getCity')->name('root.city');
                        Route::post('root/kab', 'RootController@getKab')->name('root.kab');
                        Route::post('root/kec', 'RootController@getKecamatan')->name('root.kec');
                        Route::resource('root', RootController::class);

                        Route::post('direksi/grid', 'DireksiController@grid')->name('direksi.grid');
                        Route::resource('direksi', DireksiController::class);

                        Route::post('vice/grid', 'ViceController@grid')->name('vice.grid');
                        Route::resource('vice', ViceController::class);

                        Route::post('divisi/grid', 'DivisiController@grid')->name('divisi.grid');
                        Route::resource('divisi', DivisiController::class);

                        Route::post('departemen/grid', 'DepartemenController@grid')->name('departemen.grid');
                        Route::resource('departemen', DepartemenController::class);

                        Route::post('cabang/grid', 'CabangController@grid')->name('cabang.grid');
                        Route::post('cabang/city', 'CabangController@getCity')->name('cabang.city');
                        Route::post('cabang/kab', 'CabangController@getKab')->name('cabang.kab');
                        Route::post('cabang/kec', 'CabangController@getKecamatan')->name('cabang.kec');
                        Route::resource('cabang', CabangController::class);

                        Route::post('cabang-pembantu/grid', 'CapemController@grid')->name('cabang-pembantu.grid');
                        Route::resource('cabang-pembantu', CapemController::class);

                        Route::post('kantor-kas/grid', 'KantorKasController@grid')->name('kantor-kas.grid');
                        Route::post('kantor-kas/city', 'KantorKasController@getCity')->name('kantor-kas.city');
                        Route::post('kantor-kas/kab', 'KantorKasController@getKab')->name('kantor-kas.kab');
                        Route::post('kantor-kas/kec', 'KantorKasController@getKecamatan')->name('kantor-kas.kec');
                        Route::resource('kantor-kas', KantorKasController::class);

                        Route::post('jabatan/select-cc', 'JabatanController@selectCc')->name('jabatan.select-cc');
                        Route::post('jabatan/ajax', 'JabatanController@selectAjax')->name('jabatan.ajax');
                        Route::post('jabatan/grid', 'JabatanController@grid')->name('jabatan.grid');
                        Route::get('jabatan/get-parent/{id}', 'JabatanController@getParentByLocation')->name('jabatan.getParentByLocation');

                        Route::resource('jabatan', JabatanController::class);
                    }
                );

            // Geografis
            Route::prefix('geografis')
                ->name('geografis.')
                ->namespace('Geografis')
                ->group(
                    function () {
                        Route::post('provinsi/grid', 'ProvinsiController@grid')->name('provinsi.grid');
                        Route::resource('provinsi', ProvinsiController::class);

                        Route::post('kab-kota/grid', 'KabKotaController@grid')->name('kab-kota.grid');
                        Route::resource('kab-kota', KabKotaController::class);

                        Route::post('kecamatan/grid', 'KecamatanController@grid')->name('kecamatan.grid');
                        Route::resource('kecamatan', KecamatanController::class);

                        Route::post('kel-desa/grid', 'KelDesaController@grid')->name('kel-desa.grid');
                        Route::resource('kel-desa', KelDesaController::class);
                    }
                );

            // Vendor
            Route::post('kategori-vendor/grid', 'KategoriVendorController@grid')->name('kategori-vendor.grid');
            Route::resource('kategori-vendor', KategoriVendorController::class);

            // Route::post('vendor/select-cc', 'VendorController@selectCc')->name('vendor.select-cc');
            Route::get('vendor/ajax', 'VendorController@ajax')->name('jabatan.ajax');
            Route::post('vendor/ajaxGetByIdUnitKerja', 'VendorController@ajaxGetByIdUnitKerja')->name('vendor.ajaxGetByIdUnitKerja');
            Route::post('vendor/grid', 'VendorController@grid')->name('vendor.grid');
            Route::resource('vendor', VendorController::class);
            Route::post('vendor/selectAjax', 'VendorController@selectAjax')->name('vendor.ajax');
            Route::post('vendor/selectAjaxAll', 'VendorController@selectAjaxAll')->name('vendor.ajaxAll');

            Route::get('jabatan-tad/ajax', 'JabatanTadController@ajax')->name('jabatan-tad.ajax');
            Route::post('jabatan-tad/grid', 'JabatanTadController@grid')->name('jabatan-tad.grid');
            Route::resource('jabatan-tad', JabatanTadController::class);

            // Sync
            Route::post('sync/grid', 'SyncController@grid')->name('sync.grid');
            Route::get('sync', 'SyncController@index')->name('sync.index');
            Route::post('sync/to/mass', 'SyncController@toMass')->name('sync.toMass');
            Route::post('sync/to/{tad}', 'SyncController@to')->name('sync.to');

            // Pertanyaan
            Route::prefix('pertanyaan')
                ->name('pertanyaan.')
                ->namespace('Pertanyaan')
                ->group(
                    function () {
                        Route::post('kategori/grid', 'KategoriController@grid')->name('kategori.grid');
                        Route::resource('kategori', 'KategoriController');

                        Route::post('tad/grid', 'PertanyaanController@grid')->name('tad.grid');
                        Route::resource('tad', 'PertanyaanController');

                        // Route::post('pertanyaan-tad/grid', 'PertanyaanTadController@grid')->name('pertanyaan-tad.grid');
                        // Route::resource('pertanyaan-tad', PertanyaanTadController::class);

                        Route::post('vendor/grid', 'PertanyaanVendorController@grid')->name('vendor.grid');
                        Route::resource('vendor', PertanyaanVendorController::class);
                    }
                );

            // Wawancara
            Route::prefix('wawancara')
                ->name('wawancara.')
                ->namespace('Wawancara')
                ->group(
                    function () {
                        Route::post('kompetensi/grid', 'KompetensiController@grid')->name('kompetensi.grid');
                        Route::resource('kompetensi', KompetensiController::class);

                        Route::post('pertanyaan/grid', 'PertanyaanController@grid')->name('pertanyaan.grid');
                        Route::resource('pertanyaan', PertanyaanController::class);
                    }
                );

            // Reward & Pembinaan
            Route::prefix('rp')
                ->name('rp.')
                ->namespace('RewardPembinaan')
                ->group(
                    function () {
                        Route::post('reward/grid', 'RewardController@grid')->name('reward.grid');
                        Route::resource('reward', RewardController::class);

                        Route::post('pembinaan/grid', 'PembinaanController@grid')->name('pembinaan.grid');
                        Route::resource('pembinaan', PembinaanController::class);
                    }
                );

            // Alasan Resign
            Route::post('reason-resign/grid', 'Resign\ReasonResignController@grid')->name('reason-resign.grid');
            Route::resource('reason-resign', 'Resign\ReasonResignController');
            Route::post('reason-resign/ajax', 'Resign\ReasonResignController@selectAjax')->name('reason-resign.ajax');

            // Jabatan
            Route::post('position/grid', 'PositionController@grid')->name('position.grid');
            Route::resource('position', PositionController::class);

            // Pendidikan
            Route::post('pendidikan/grid', 'PendidikanController@grid')->name('pendidikan.grid');
            Route::resource('pendidikan', PendidikanController::class);

            // Jurusan
            Route::post('jurusan/grid', 'JurusanController@grid')->name('jurusan.grid');
            Route::resource('jurusan', JurusanController::class);
        }
    );
