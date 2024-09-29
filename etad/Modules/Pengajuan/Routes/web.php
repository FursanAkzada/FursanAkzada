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

Route::prefix('pengajuan')
    ->name('pengajuan.')
    ->middleware(['auth', 'notified'])
    ->group(
        function () {
            // Route::get('/', 'PengajuanController@index');
            Route::namespace('Tad')
                ->group(
                    function () {

                        /* Pengajuan */
                        Route::prefix('pengajuan')
                            ->name('pengajuan.')
                            ->group(
                                function () {
                                    Route::get('kandidat/{requirement}', 'PengajuanController@kandidat')->name('kandidat');
                                    Route::post('kandidat/{requirement}', 'PengajuanController@kandidatStore')->name('kandidat.store');
                                    Route::post('kandidat/{requirement}/grid', 'PengajuanController@kandidatGrid')->name('kandidat.grid');
                                    Route::post('kandidat-send/{otorisasi_vendor}', 'PengajuanController@kandidatSend')->name('kandidat.send');

                                    // CC
                                    Route::post('cc/grid/{ra}', 'PengajuanController@ccGrid')->name('cc.grid');
                                    Route::get('cc/{ra}', 'PengajuanController@ccCreate')->name('cc.create');
                                    Route::post('cc/{ra}', 'PengajuanController@ccStore')->name('cc.store');
                                    Route::get('cc/edit/{cc}', 'PengajuanController@ccEdit')->name('cc.edit');
                                    Route::put('cc/update/{cc}', 'PengajuanController@ccUpdate')->name('cc.update');
                                    Route::delete('cc/{cc}', 'PengajuanController@ccDestroy')->name('cc.destroy');

                                    Route::post('send/interview-mail/{pengajuan}', 'PengajuanController@sendInterviewMail')->name('send.interview.mail');
                                    Route::post('send/interview-mail/personal/{tad}', 'PengajuanController@sendInterviewMailPersonal')->name('send.interview.mail.personal');
                                }
                            );
                        Route::post('pengajuan/grid', 'PengajuanController@grid')->name('pengajuan.grid');
                        Route::post('pengajuan/requirement/grid/{form}', 'PengajuanController@requirementGrid')->name('pengajuan.requirement.grid');
                        Route::post('pengajuan/{id}/approval', 'PengajuanController@approvalSave')->name('pengajuan.approvalSave');
                        Route::delete('pengajuan/requirement/delete/{requirement}', 'PengajuanController@requirementDelete')->name('pengajuan.requirement.delete');
                        Route::get('pengajuan/{id}/print', 'PengajuanController@print')->name('pengajuan.print');
                        Route::get('pengajuan/{id}/riwayat', 'PengajuanController@riwayat')->name('pengajuan.riwayat');
                        Route::get('pengajuan/{id}/tracking', 'PengajuanController@tracking')->name('pengajuan.tracking');
                        Route::resource('pengajuan', PengajuanController::class);


                        /* Kandidat */
                        Route::get('kandidat/{id}/edit', 'PengajuanKandidatController@edit')->name('kandidat.edit');
                        Route::get('kandidat/{id}/detailCreate', 'PengajuanKandidatController@detailCreate')->name('kandidat.detailCreate');
                        Route::post('kandidat/{pengajuan}/detailStore', 'PengajuanKandidatController@detailStore')->name('kandidat.detailStore');
                        Route::post('kandidat/grid', 'PengajuanKandidatController@grid')->name('kandidat.grid');
                        Route::delete('kandidat/kandidatDestroy/{id}', 'PengajuanKandidatController@kandidatDestroy')->name('kandidat.kandidatDestroy');
                        Route::post('kandidat/calonKandidatGrid/{pengajuan}', 'PengajuanKandidatController@calonKandidatGrid')->name('kandidat.calonKandidat.grid');
                        Route::post('kandidat/personil/grid/{pengajuan}', 'PengajuanKandidatController@personilGrid')->name('kandidat.personil.grid');
                        Route::post('kandidat/personil/gridShow/{pengajuan}', 'PengajuanKandidatController@personilGridShow')->name('kandidat.personil.grid_show');
                        Route::post('kandidat/{id}/approval', 'PengajuanKandidatController@approvalSave')->name('kandidat.approvalSave');
                        Route::get('kandidat/{id}/riwayat', 'PengajuanKandidatController@riwayat')->name('kandidat.riwayat');
                        Route::get('kandidat/{id}/tracking', 'PengajuanKandidatController@tracking')->name('kandidat.tracking');
                        Route::get('kandidat/{id}/approval', 'PengajuanKandidatController@approval')->name('kandidat.approval');
                        Route::post('kandidat/{id}/approval-save', 'PengajuanKandidatController@approvalSave')->name('kandidat.approvalSave');
                        Route::resource('kandidat', PengajuanKandidatController::class);
                        Route::get('kandidat/{id}/print', 'PengajuanKandidatController@print')->name('kandidat.print');


                        /* Pengajuan Wawancara */
                        Route::get('wawancara/{id}/edit', 'PengajuanWawancaraController@edit')->name('wawancara.edit');
                        Route::get('wawancara/{id}/detailCreate', 'PengajuanWawancaraController@detailCreate')->name('wawancara.detailCreate');
                        Route::post('wawancara/{pengajuan}/detailStore', 'PengajuanWawancaraController@detailStore')->name('wawancara.detailStore');
                        Route::post('wawancara/grid', 'PengajuanWawancaraController@grid')->name('wawancara.grid');
                        Route::delete('wawancara/kandidatDestroy/{id}', 'PengajuanWawancaraController@kandidatDestroy')->name('wawancara.kandidatDestroy');
                        Route::post('wawancara/calonKandidatGrid/{pengajuan}', 'PengajuanWawancaraController@calonKandidatGrid')->name('wawancara.calonKandidat.grid');
                        Route::post('wawancara/personil/grid/{pengajuan}', 'PengajuanWawancaraController@personilGrid')->name('wawancara.personil.grid');
                        Route::get('wawancara/{id}/approval', 'PengajuanWawancaraController@approval')->name('wawancara.approval');
                        Route::post('wawancara/{id}/saveApproval', 'PengajuanWawancaraController@approvalSave')->name('wawancara.approvalSave');
                        Route::get('wawancara/{id}/riwayat', 'PengajuanWawancaraController@riwayat')->name('wawancara.riwayat');
                        Route::get('wawancara/{id}/tracking', 'PengajuanWawancaraController@tracking')->name('wawancara.tracking');
                        Route::get('wawancara/{id}/pengajuanMundur', 'PengajuanWawancaraController@pengajuanMundur')->name('wawancara.pengajuanMundur');
                        Route::post('wawancara/{id}/handlePengajuanMundur', 'PengajuanWawancaraController@handlePengajuanMundur')->name('wawancara.handlePengajuanMundur');
                        Route::post('wawancara/{id}/approval-save', 'PengajuanWawancaraController@approvalSave')->name('wawancara.approvalSave');
                        Route::resource('wawancara', PengajuanWawancaraController::class);
                        Route::get('wawancara/{id}/print', 'PengajuanWawancaraController@print')->name('wawancara.print');


                        /* Pengajuan Penrimaan */
                        Route::get('penerimaan/{id}/edit', 'PengajuanPenerimaanController@edit')->name('penerimaan.edit');
                        Route::get('penerimaan/{id}/detailCreate', 'PengajuanPenerimaanController@detailCreate')->name('penerimaan.detailCreate');
                        Route::post('penerimaan/{pengajuan}/detailStore', 'PengajuanPenerimaanController@detailStore')->name('penerimaan.detailStore');
                        Route::post('penerimaan/grid', 'PengajuanPenerimaanController@grid')->name('penerimaan.grid');
                        Route::delete('penerimaan/kandidatDestroy/{id}', 'PengajuanPenerimaanController@kandidatDestroy')->name('penerimaan.kandidatDestroy');
                        Route::post('penerimaan/calonKandidatGrid/{pengajuan}', 'PengajuanPenerimaanController@calonKandidatGrid')->name('penerimaan.calonKandidat.grid');
                        Route::post('penerimaan/personil/grid/{pengajuan}', 'PengajuanPenerimaanController@personilGrid')->name('penerimaan.personil.grid');
                        Route::get('penerimaan/{id}/approval', 'PengajuanPenerimaanController@approval')->name('penerimaan.approval');
                        Route::post('penerimaan/{id}/saveApproval', 'PengajuanPenerimaanController@approvalSave')->name('penerimaan.approvalSave');
                        Route::get('penerimaan/{id}/riwayat', 'PengajuanPenerimaanController@riwayat')->name('penerimaan.riwayat');
                        Route::get('penerimaan/{id}/tracking', 'PengajuanPenerimaanController@tracking')->name('penerimaan.tracking');
                        Route::post('penerimaan/{id}/approval-save', 'PengajuanPenerimaanController@approvalSave')->name('penerimaan.approvalSave');
                        Route::resource('penerimaan', PengajuanPenerimaanController::class);
                        Route::get('penerimaan/{id}/print', 'PengajuanPenerimaanController@print')->name('penerimaan.print');

                        /* Otorisasi Vendor */
                        Route::prefix('otorisasi-vendor')
                            ->name('otorisasi.vendor.')
                            ->group(
                                function () {
                                    Route::post('grid', 'OtorisasiVendorController@grid')->name('grid');
                                    Route::post('requirement/grid/{otorisasi_vendor}', 'OtorisasiVendorController@requirementGrid')->name('requirement.grid');
                                    Route::post('send/{otorisasi_vendor}', 'OtorisasiVendorController@send')->name('send');

                                    Route::post('kandidat/{requirement}/grid', 'OtorisasiVendorController@kandidatGrid')->name('kandidat.grid');
                                    Route::get('kandidat/{requirement}', 'OtorisasiVendorController@kandidatForm')->name('kandidat');
                                    Route::post('kandidat/{requirement}', 'OtorisasiVendorController@kandidatStore')->name('kandidat.store');
                                    Route::get('kandidat/{tad}/show', 'OtorisasiVendorController@kandidatShow')->name('kandidat.show');
                                    Route::post('kandidat/keluarga/{tad}/grid', 'OtorisasiVendorController@keluargaGrid')->name('kandidat.keluarga.grid');

                                    Route::post('kandidat/wawancara/{tad}/grid', 'OtorisasiVendorController@wawancaraGrid')->name('kandidat.wawancara.grid');
                                    Route::get('kandidat/wawancara/{wawancara}/show', 'OtorisasiVendorController@wawancaraShow')->name('kandidat.wawancara.show');

                                    /* reprocess */
                                    Route::get('reprocess/{pengajuan}', 'OtorisasiVendorController@reprocess')->name('reprocess');
                                    Route::post('reprocess/requirement/grid/{pengajuan}', 'OtorisasiVendorController@reprocessRequirementGrid')->name('reprocess.requirement.grid');

                                    Route::get('reprocess/kandidat/{requirement}', 'OtorisasiVendorController@reprocessKandidat')->name('reprocess.kandidat');
                                    Route::post('reprocess/kandidat/grid/{requirement}', 'OtorisasiVendorController@reprocessKandidatGrid')->name('reprocess.kandidat.grid');
                                    /* eof reprocess */
                                }
                            );
                        Route::resource('otorisasi-vendor', OtorisasiVendorController::class)->names('otorisasi.vendor');
                        /* Eof Otorisasi Vendor */
                    }
                );

            Route::resource('mutasi', 'Mutasi\PengajuanController');
            Route::prefix('mutasi')
                ->name('mutasi.')
                ->namespace('Mutasi')
                ->group(
                    function () {
                        Route::post('grid', 'PengajuanController@grid')->name('grid');
                        Route::post('mutasi/grid/{tad}', 'PengajuanController@mutasiGrid')->name('mutasi.grid');
                        Route::get('mutasi/create/{tad}', 'PengajuanController@create')->name('mutasi.create');
                        Route::post('mutasi/create/{tad}', 'PengajuanController@store')->name('mutasi.store');
                        Route::get('mutasi/edit/{mutasi}', 'PengajuanController@edit')->name('mutasi.edit');
                        Route::get('mutasi/history/{mutasi}', 'PengajuanController@mutasiHistory')->name('mutasi.history');
                        Route::put('mutasi/update/{mutasi}', 'PengajuanController@update')->name('mutasi.update');
                        Route::get('mutasi/{form}', 'PengajuanController@mutasiShow')->name('mutasi.show');
                        Route::post('mutasi/approval/{mutasi}', 'PengajuanController@approvalSave')->name('approvalSave');
                        Route::post('{id}/approval', 'PengajuanController@approvalSave')->name('approvalSave');
                        Route::get('{id}/print', 'PengajuanController@print')->name('print');
                        Route::get('{id}/riwayat', 'PengajuanController@riwayat')->name('riwayat');
                        Route::get('{id}/tracking', 'PengajuanController@tracking')->name('tracking');
                    }
                );

            Route::resource('resign', 'Resign\PengajuanController');
            Route::prefix('resign')
                ->name('resign.')
                ->namespace('Resign')
                ->group(
                    function () {
                        Route::post('resignGrid/{pengajuan}', 'PengajuanController@resignGrid')->name('resign.grid');
                        Route::get('resignGridShow/{pengajuan}', 'PengajuanController@resignGridShow')->name('resign.grid.show');
                        Route::post('grid', 'PengajuanController@grid')->name('grid');
			Route::post('{id}/approvalSave', 'PengajuanController@approvalSave')->name('approvalSave');
                        Route::get('{id}/approval', 'PengajuanController@approval')->name('approval');
		        Route::delete('pegawai/delete/{pegawai}', 'PengajuanController@pegawaiDelete')->name('pegawai.delete');
                        Route::get('{id}/print', 'PengajuanController@print')->name('print');
                        Route::get('{id}/riwayat', 'PengajuanController@riwayat')->name('riwayat');
                        Route::get('{id}/tracking', 'PengajuanController@tracking')->name('tracking');
                    }
                );
        }
    );

Route::name('personil.')
    ->middleware(['auth', 'notified'])
    ->prefix('personil')
    ->group(
        function ($q) {
            // Personil TAD
            Route::prefix('migrasi')
                ->name('migrasi.')
                ->group(
                    function () {
                        Route::get('import', 'Tad\PersonilController@import')->name('import');
                        Route::get('import-save', 'Tad\PersonilController@importSave')->name('import-save');
                        Route::get('importSaveConfirm/{tipe}', 'Tad\PersonilController@importSaveConfirm')->name('importSaveConfirm');
                        Route::get('importSaveConfirmSave', 'Tad\PersonilController@importSaveConfirmSave')->name('importSaveConfirmSave');
                        Route::post('grid', 'Tad\PersonilController@grid')->name('grid');
                        Route::post('kota', 'Tad\PersonilController@kota')->name('kota');
                        Route::post('jurusan', 'Tad\PersonilController@jurusan')->name('jurusan');
                        Route::post('{id}/riwayat-kepegawaian-grid', 'Tad\PersonilController@riwayatKepegawaianGrid')->name('riwayat-kepegawaian.grid');
                        Route::post('{id}/reward-grid', 'Tad\PersonilController@riwayatRewardGrid')->name('reward.grid');

                        Route::post('keluarga/{personil}/grid', 'Tad\PersonilController@keluargaGrid')->name('keluarga.grid');
                        Route::get('keluarga/{personil}/show', 'Tad\PersonilController@keluargaShow')->name('keluarga.show');
                        Route::get('keluarga/{personil}/detailCreate', 'Tad\PersonilController@keluargaDetailCreate')->name('keluarga.detailCreate');
                        Route::get('keluarga/{keluarga}/detailEdit', 'Tad\PersonilController@keluargaDetailEdit')->name('keluarga.detailEdit');
                        Route::get('keluarga/{keluarga}/detailShow', 'Tad\PersonilController@keluargaDetailShow')->name('keluarga.detailShow');
                        Route::get('keluarga/{personil}', 'Tad\PersonilController@keluarga')->name('keluarga');
                        Route::post('keluarga/{personil}', 'Tad\PersonilController@keluargaStore')->name('keluarga.store');
                        Route::post('keluarga/{id}/detailUpdate', 'Tad\PersonilController@keluargaDetailUpdate')->name('keluarga.detailUpdate');
                        Route::put('keluarga/{personil}', 'Tad\PersonilController@keluargaUpdate')->name('keluarga.update');
                        Route::delete('keluarga/id/{keluarga}', 'Tad\PersonilController@keluargaDestroy')->name('keluarga.destroy');

                        // riwayat kerja
                        Route::get('riwayatKerja/{personil}', 'Tad\PersonilController@riwayatKerja')->name('riwayatKerja');
                        Route::post('riwayatKerja/{personil}/grid', 'Tad\PersonilController@riwayatKerjaGrid')->name('riwayatKerja.grid');
                        Route::get('riwayatKerja/{personil}/create', 'Tad\PersonilController@riwayatKerjaCreate')->name('riwayatKerja.create');
                        Route::post('riwayatKerja/{personil}', 'Tad\PersonilController@riwayatKerjaStore')->name('riwayatKerja.store');
                        Route::get('riwayatKerja/{id}/edit', 'Tad\PersonilController@riwayatKerjaEdit')->name('riwayatKerja.edit');
                        Route::post('riwayatKerja/{id}/update', 'Tad\PersonilController@riwayatKerjaUpdate')->name('riwayatKerja.update');
                        Route::get('riwayatKerja/{id}/show', 'Tad\PersonilController@riwayatKerjaShow')->name('riwayatKerja.show');
                        Route::delete('riwayatKerja/{id}/destroy', 'Tad\PersonilController@riwayatKerjaDestroy')->name('riwayatKerja.destroy');

                        Route::post('wawancara/{personil}/grid', 'Tad\PersonilController@wawancaraGrid')->name('wawancara.grid');
                        Route::get('wawancara/{personil}/create', 'Tad\PersonilController@wawancaraCreate')->name('wawancara.create');
                        Route::post('wawancara/{personil}/store', 'Tad\PersonilController@wawancaraStore')->name('wawancara.store');
                        Route::get('wawancara/{wawancara}/show', 'Tad\PersonilController@wawancaraShow')->name('wawancara.show');
                        Route::get('wawancara/{wawancara}/edit', 'Tad\PersonilController@wawancaraEdit')->name('wawancara.edit');
                        Route::put('wawancara/{personil}/update', 'Tad\PersonilController@wawancaraUpdate')->name('wawancara.update');
                        Route::delete('wawancara/{personil}/delete', 'Tad\PersonilController@wawancaraDelete')->name('wawancara.delete');

                        Route::post('wawancara-hc/{personil}/grid', 'Tad\PersonilController@wawancaraHcGrid')->name('wawancara-hc.grid');

                        Route::get('kandidat/{kandidat}/edit', 'Tad\PersonilController@kandidatEdit')->name('kandidat.edit');
                        Route::put('kandidat/{kandidat}', 'Tad\PersonilController@kandidatUpdate')->name('kandidat.update');
                        Route::get('kandidat/wawancara/{kandidat}', 'Tad\PersonilController@wawancaraHcCreate')->name('kandidat.wawancara.create');
                        Route::post('kandidat/wawancara/{kandidat}', 'Tad\PersonilController@wawancaraHcStore')->name('kandidat.wawancara.store');
                        Route::get('kandidat/wawancara/{id}/show', 'Tad\PersonilController@wawancaraHcShow')->name('kandidat.wawancara.show');
                        Route::get('kandidat/wawancara/{wawancara}/edit', 'Tad\PersonilController@wawancaraHcEdit')->name('kandidat.wawancara.edit');
                        Route::put('kandidat/wawancara/{wawancara}/update', 'Tad\PersonilController@wawancaraHcUpdate')->name('kandidat.wawancara.update');
                        Route::delete('kandidat/wawancara/{wawancara}', 'Tad\PersonilController@wawancaraHcDelete')->name('kandidat.wawancara.delete');

                        Route::get('kandidat/penerimaan/{kandidat}', 'Tad\PersonilController@penerimaanCreate')->name('kandidat.penerimaan.create');
                        Route::post('kandidat/penerimaan/{kandidat}', 'Tad\PersonilController@penerimaanStore')->name('kandidat.penerimaan.store');
                        Route::post('kandidat/penolakan/{kandidat}', 'Tad\PersonilController@penolakanStore')->name('kandidat.penolakan.store');
                    }
                );

            Route::get('migrasi/ajax', 'Tad\PersonilController@ajax')->name('migrasi.getAjaxResignMutasi');
            Route::post('migrasi/ajax', 'Tad\PersonilController@ajaxPenilaianTAD')->name('migrasi.getAjaxPenilaianTAD');
            Route::post('migrasi/ajaxCekJabatan', 'Tad\PersonilController@ajaxPenilaianTADCekJabatan')->name('migrasi.getAjaxPenilaianTADCekJabatan');
            Route::post('migrasi/ajaxCekPosisiNo', 'Tad\PersonilController@ajaxPenilaianTADCekPosisi')->name('migrasi.getAjaxPenilaianTADCekPosisi');
            Route::post('migrasi/ajaxCekPosisiNoResign', 'Tad\PersonilController@ajaxPenilaianTADCekPosisiNoResign')->name('migrasi.getajaxPenilaianTADCekPosisiNoResign');

            Route::resource('migrasi', 'Tad\PersonilController');

            // Personil Aktif
            Route::resource('aktif', 'Tad\PersonilAktifController');
            // Personil TAD
            Route::prefix('aktif')
                ->name('aktif.')
                ->group(
                    function () {
                        Route::get('import', 'Tad\PersonilAktifController@import')->name('import');
                        Route::post('importSave', 'Tad\PersonilAktifController@importSave')->name('importSave');
                        Route::post('grid', 'Tad\PersonilAktifController@grid')->name('grid');
                        Route::post('kota', 'Tad\PersonilAktifController@kota')->name('kota');
                        Route::post('jurusan', 'Tad\PersonilAktifController@jurusan')->name('jurusan');
                        Route::post('{id}/riwayat-kepegawaian-grid', 'Tad\PersonilAktifController@riwayatKepegawaianGrid')->name('riwayat-kepegawaian.grid');
                        Route::post('{id}/reward-grid', 'Tad\PersonilAktifController@riwayatRewardGrid')->name('reward.grid');

                        Route::post('keluarga/{personil}/grid', 'Tad\PersonilAktifController@keluargaGrid')->name('keluarga.grid');
                        Route::get('keluarga/{personil}/show', 'Tad\PersonilAktifController@keluargaShow')->name('keluarga.show');
                        Route::get('keluarga/{personil}/detailCreate', 'Tad\PersonilAktifController@keluargaDetailCreate')->name('keluarga.detailCreate');
                        Route::get('keluarga/{keluarga}/detailEdit', 'Tad\PersonilAktifController@keluargaDetailEdit')->name('keluarga.detailEdit');
                        Route::get('keluarga/{keluarga}/detailShow', 'Tad\PersonilAktifController@keluargaDetailShow')->name('keluarga.detailShow');
                        Route::get('keluarga/{personil}', 'Tad\PersonilAktifController@keluarga')->name('keluarga');
                        Route::post('keluarga/{personil}', 'Tad\PersonilAktifController@keluargaStore')->name('keluarga.store');
                        Route::post('keluarga/{id}/detailUpdate', 'Tad\PersonilAktifController@keluargaDetailUpdate')->name('keluarga.detailUpdate');
                        Route::put('keluarga/{personil}', 'Tad\PersonilAktifController@keluargaUpdate')->name('keluarga.update');
                        Route::delete('keluarga/id/{keluarga}', 'Tad\PersonilAktifController@keluargaDestroy')->name('keluarga.destroy');

                        // riwayat kerja
                        Route::get('riwayatKerja/{personil}', 'Tad\PersonilAktifController@riwayatKerja')->name('riwayatKerja');
                        Route::post('riwayatKerja/{personil}/grid', 'Tad\PersonilAktifController@riwayatKerjaGrid')->name('riwayatKerja.grid');
                        Route::get('riwayatKerja/{personil}/create', 'Tad\PersonilAktifController@riwayatKerjaCreate')->name('riwayatKerja.create');
                        Route::post('riwayatKerja/{personil}', 'Tad\PersonilAktifController@riwayatKerjaStore')->name('riwayatKerja.store');
                        Route::get('riwayatKerja/{id}/edit', 'Tad\PersonilAktifController@riwayatKerjaEdit')->name('riwayatKerja.edit');
                        Route::post('riwayatKerja/{id}/update', 'Tad\PersonilAktifController@riwayatKerjaUpdate')->name('riwayatKerja.update');
                        Route::get('riwayatKerja/{id}/show', 'Tad\PersonilAktifController@riwayatKerjaShow')->name('riwayatKerja.show');
                        Route::delete('riwayatKerja/{id}/destroy', 'Tad\PersonilAktifController@riwayatKerjaDestroy')->name('riwayatKerja.destroy');

                        Route::post('wawancara/{personil}/grid', 'Tad\PersonilAktifController@wawancaraGrid')->name('wawancara.grid');
                        Route::get('wawancara/{personil}/create', 'Tad\PersonilAktifController@wawancaraCreate')->name('wawancara.create');
                        Route::post('wawancara/{personil}/store', 'Tad\PersonilAktifController@wawancaraStore')->name('wawancara.store');
                        Route::get('wawancara/{wawancara}/show', 'Tad\PersonilAktifController@wawancaraShow')->name('wawancara.show');
                        Route::get('wawancara/{wawancara}/edit', 'Tad\PersonilAktifController@wawancaraEdit')->name('wawancara.edit');
                        Route::put('wawancara/{personil}/update', 'Tad\PersonilAktifController@wawancaraUpdate')->name('wawancara.update');
                        Route::delete('wawancara/{personil}/delete', 'Tad\PersonilAktifController@wawancaraDelete')->name('wawancara.delete');

                        Route::post('wawancara-hc/{personil}/grid', 'Tad\PersonilAktifController@wawancaraHcGrid')->name('wawancara-hc.grid');

                        Route::get('kandidat/{kandidat}/edit', 'Tad\PersonilAktifController@kandidatEdit')->name('kandidat.edit');
                        Route::put('kandidat/{kandidat}', 'Tad\PersonilAktifController@kandidatUpdate')->name('kandidat.update');
                        Route::get('kandidat/wawancara/{kandidat}', 'Tad\PersonilAktifController@wawancaraHcCreate')->name('kandidat.wawancara.create');
                        Route::post('kandidat/wawancara/{kandidat}', 'Tad\PersonilAktifController@wawancaraHcStore')->name('kandidat.wawancara.store');
                        Route::get('kandidat/wawancara/{id}/show', 'Tad\PersonilAktifController@wawancaraHcShow')->name('kandidat.wawancara.show');
                        Route::get('kandidat/wawancara/{wawancara}/edit', 'Tad\PersonilAktifController@wawancaraHcEdit')->name('kandidat.wawancara.edit');
                        Route::put('kandidat/wawancara/{wawancara}/update', 'Tad\PersonilAktifController@wawancaraHcUpdate')->name('kandidat.wawancara.update');
                        Route::delete('kandidat/wawancara/{wawancara}', 'Tad\PersonilAktifController@wawancaraHcDelete')->name('kandidat.wawancara.delete');

                        Route::get('kandidat/penerimaan/{kandidat}', 'Tad\PersonilAktifController@penerimaanCreate')->name('kandidat.penerimaan.create');
                        Route::post('kandidat/penerimaan/{kandidat}', 'Tad\PersonilAktifController@penerimaanStore')->name('kandidat.penerimaan.store');
                        Route::post('kandidat/penolakan/{kandidat}', 'Tad\PersonilAktifController@penolakanStore')->name('kandidat.penolakan.store');
                    }
                );

            // Personil Nonaktif
            Route::resource('nonaktif', 'Tad\PersonilNonaktifController');
            // Personil TAD
            Route::prefix('nonaktif')
                ->name('nonaktif.')
                ->group(
                    function () {
                        Route::get('import', 'Tad\PersonilNonaktifController@import')->name('import');
                        Route::post('importSave', 'Tad\PersonilNonaktifController@importSave')->name('importSave');
                        Route::post('grid', 'Tad\PersonilNonaktifController@grid')->name('grid');
                        Route::post('kota', 'Tad\PersonilNonaktifController@kota')->name('kota');
                        Route::post('jurusan', 'Tad\PersonilNonaktifController@jurusan')->name('jurusan');
                        Route::post('{id}/riwayat-kepegawaian-grid', 'Tad\PersonilNonaktifController@riwayatKepegawaianGrid')->name('riwayat-kepegawaian.grid');
                        Route::post('{id}/reward-grid', 'Tad\PersonilNonaktifController@riwayatRewardGrid')->name('reward.grid');

                        Route::post('keluarga/{personil}/grid', 'Tad\PersonilNonaktifController@keluargaGrid')->name('keluarga.grid');
                        Route::get('keluarga/{personil}/show', 'Tad\PersonilNonaktifController@keluargaShow')->name('keluarga.show');
                        Route::get('keluarga/{personil}/detailCreate', 'Tad\PersonilNonaktifController@keluargaDetailCreate')->name('keluarga.detailCreate');
                        Route::get('keluarga/{keluarga}/detailEdit', 'Tad\PersonilNonaktifController@keluargaDetailEdit')->name('keluarga.detailEdit');
                        Route::get('keluarga/{keluarga}/detailShow', 'Tad\PersonilNonaktifController@keluargaDetailShow')->name('keluarga.detailShow');
                        Route::get('keluarga/{personil}', 'Tad\PersonilNonaktifController@keluarga')->name('keluarga');
                        Route::post('keluarga/{personil}', 'Tad\PersonilNonaktifController@keluargaStore')->name('keluarga.store');
                        Route::post('keluarga/{id}/detailUpdate', 'Tad\PersonilNonaktifController@keluargaDetailUpdate')->name('keluarga.detailUpdate');
                        Route::put('keluarga/{personil}', 'Tad\PersonilNonaktifController@keluargaUpdate')->name('keluarga.update');
                        Route::delete('keluarga/id/{keluarga}', 'Tad\PersonilNonaktifController@keluargaDestroy')->name('keluarga.destroy');

                        // riwayat kerja
                        Route::get('riwayatKerja/{personil}', 'Tad\PersonilNonaktifController@riwayatKerja')->name('riwayatKerja');
                        Route::post('riwayatKerja/{personil}/grid', 'Tad\PersonilNonaktifController@riwayatKerjaGrid')->name('riwayatKerja.grid');
                        Route::get('riwayatKerja/{personil}/create', 'Tad\PersonilNonaktifController@riwayatKerjaCreate')->name('riwayatKerja.create');
                        Route::post('riwayatKerja/{personil}', 'Tad\PersonilNonaktifController@riwayatKerjaStore')->name('riwayatKerja.store');
                        Route::get('riwayatKerja/{id}/edit', 'Tad\PersonilNonaktifController@riwayatKerjaEdit')->name('riwayatKerja.edit');
                        Route::post('riwayatKerja/{id}/update', 'Tad\PersonilNonaktifController@riwayatKerjaUpdate')->name('riwayatKerja.update');
                        Route::get('riwayatKerja/{id}/show', 'Tad\PersonilNonaktifController@riwayatKerjaShow')->name('riwayatKerja.show');
                        Route::delete('riwayatKerja/{id}/destroy', 'Tad\PersonilNonaktifController@riwayatKerjaDestroy')->name('riwayatKerja.destroy');

                        Route::post('wawancara/{personil}/grid', 'Tad\PersonilNonaktifController@wawancaraGrid')->name('wawancara.grid');
                        Route::get('wawancara/{personil}/create', 'Tad\PersonilNonaktifController@wawancaraCreate')->name('wawancara.create');
                        Route::post('wawancara/{personil}/store', 'Tad\PersonilNonaktifController@wawancaraStore')->name('wawancara.store');
                        Route::get('wawancara/{wawancara}/show', 'Tad\PersonilNonaktifController@wawancaraShow')->name('wawancara.show');
                        Route::get('wawancara/{wawancara}/edit', 'Tad\PersonilNonaktifController@wawancaraEdit')->name('wawancara.edit');
                        Route::put('wawancara/{personil}/update', 'Tad\PersonilNonaktifController@wawancaraUpdate')->name('wawancara.update');
                        Route::delete('wawancara/{personil}/delete', 'Tad\PersonilNonaktifController@wawancaraDelete')->name('wawancara.delete');

                        Route::post('wawancara-hc/{personil}/grid', 'Tad\PersonilNonaktifController@wawancaraHcGrid')->name('wawancara-hc.grid');

                        Route::get('kandidat/{kandidat}/edit', 'Tad\PersonilNonaktifController@kandidatEdit')->name('kandidat.edit');
                        Route::put('kandidat/{kandidat}', 'Tad\PersonilNonaktifController@kandidatUpdate')->name('kandidat.update');
                        Route::get('kandidat/wawancara/{kandidat}', 'Tad\PersonilNonaktifController@wawancaraHcCreate')->name('kandidat.wawancara.create');
                        Route::post('kandidat/wawancara/{kandidat}', 'Tad\PersonilNonaktifController@wawancaraHcStore')->name('kandidat.wawancara.store');
                        Route::get('kandidat/wawancara/{id}/show', 'Tad\PersonilNonaktifController@wawancaraHcShow')->name('kandidat.wawancara.show');
                        Route::get('kandidat/wawancara/{wawancara}/edit', 'Tad\PersonilNonaktifController@wawancaraHcEdit')->name('kandidat.wawancara.edit');
                        Route::put('kandidat/wawancara/{wawancara}/update', 'Tad\PersonilNonaktifController@wawancaraHcUpdate')->name('kandidat.wawancara.update');
                        Route::delete('kandidat/wawancara/{wawancara}', 'Tad\PersonilNonaktifController@wawancaraHcDelete')->name('kandidat.wawancara.delete');

                        Route::get('kandidat/penerimaan/{kandidat}', 'Tad\PersonilNonaktifController@penerimaanCreate')->name('kandidat.penerimaan.create');
                        Route::post('kandidat/penerimaan/{kandidat}', 'Tad\PersonilNonaktifController@penerimaanStore')->name('kandidat.penerimaan.store');
                        Route::post('kandidat/penolakan/{kandidat}', 'Tad\PersonilNonaktifController@penolakanStore')->name('kandidat.penolakan.store');
                    }
                );

            // Personil Belum Bekerja
            Route::resource('belum-bekerja', 'Tad\PersonilUnemployedController');
            // Personil TAD
            Route::prefix('belum-bekerja')
                ->name('belum-bekerja.')
                ->group(
                    function () {
                        Route::get('import', 'Tad\PersonilUnemployedController@import')->name('import');
                        Route::post('importSave', 'Tad\PersonilUnemployedController@importSave')->name('importSave');
                        Route::post('grid', 'Tad\PersonilUnemployedController@grid')->name('grid');
                        Route::post('kota', 'Tad\PersonilUnemployedController@kota')->name('kota');
                        Route::post('jurusan', 'Tad\PersonilUnemployedController@jurusan')->name('jurusan');
                        Route::post('{id}/riwayat-kepegawaian-grid', 'Tad\PersonilUnemployedController@riwayatKepegawaianGrid')->name('riwayat-kepegawaian.grid');
                        Route::post('{id}/reward-grid', 'Tad\PersonilUnemployedController@riwayatRewardGrid')->name('reward.grid');

                        Route::post('keluarga/{personil}/grid', 'Tad\PersonilUnemployedController@keluargaGrid')->name('keluarga.grid');
                        Route::get('keluarga/{personil}/show', 'Tad\PersonilUnemployedController@keluargaShow')->name('keluarga.show');
                        Route::get('keluarga/{personil}/detailCreate', 'Tad\PersonilUnemployedController@keluargaDetailCreate')->name('keluarga.detailCreate');
                        Route::get('keluarga/{keluarga}/detailEdit', 'Tad\PersonilUnemployedController@keluargaDetailEdit')->name('keluarga.detailEdit');
                        Route::get('keluarga/{keluarga}/detailShow', 'Tad\PersonilUnemployedController@keluargaDetailShow')->name('keluarga.detailShow');
                        Route::get('keluarga/{personil}', 'Tad\PersonilUnemployedController@keluarga')->name('keluarga');
                        Route::post('keluarga/{personil}', 'Tad\PersonilUnemployedController@keluargaStore')->name('keluarga.store');
                        Route::post('keluarga/{id}/detailUpdate', 'Tad\PersonilUnemployedController@keluargaDetailUpdate')->name('keluarga.detailUpdate');
                        Route::put('keluarga/{personil}', 'Tad\PersonilUnemployedController@keluargaUpdate')->name('keluarga.update');
                        Route::delete('keluarga/id/{keluarga}', 'Tad\PersonilUnemployedController@keluargaDestroy')->name('keluarga.destroy');

                        // riwayat kerja
                        Route::get('riwayatKerja/{personil}', 'Tad\PersonilUnemployedController@riwayatKerja')->name('riwayatKerja');
                        Route::post('riwayatKerja/{personil}/grid', 'Tad\PersonilUnemployedController@riwayatKerjaGrid')->name('riwayatKerja.grid');
                        Route::get('riwayatKerja/{personil}/create', 'Tad\PersonilUnemployedController@riwayatKerjaCreate')->name('riwayatKerja.create');
                        Route::post('riwayatKerja/{personil}', 'Tad\PersonilUnemployedController@riwayatKerjaStore')->name('riwayatKerja.store');
                        Route::get('riwayatKerja/{id}/edit', 'Tad\PersonilUnemployedController@riwayatKerjaEdit')->name('riwayatKerja.edit');
                        Route::post('riwayatKerja/{id}/update', 'Tad\PersonilUnemployedController@riwayatKerjaUpdate')->name('riwayatKerja.update');
                        Route::get('riwayatKerja/{id}/show', 'Tad\PersonilUnemployedController@riwayatKerjaShow')->name('riwayatKerja.show');
                        Route::delete('riwayatKerja/{id}/destroy', 'Tad\PersonilUnemployedController@riwayatKerjaDestroy')->name('riwayatKerja.destroy');

                        Route::post('wawancara/{personil}/grid', 'Tad\PersonilUnemployedController@wawancaraGrid')->name('wawancara.grid');
                        Route::get('wawancara/{personil}/create', 'Tad\PersonilUnemployedController@wawancaraCreate')->name('wawancara.create');
                        Route::post('wawancara/{personil}/store', 'Tad\PersonilUnemployedController@wawancaraStore')->name('wawancara.store');
                        Route::get('wawancara/{wawancara}/show', 'Tad\PersonilUnemployedController@wawancaraShow')->name('wawancara.show');
                        Route::get('wawancara/{wawancara}/edit', 'Tad\PersonilUnemployedController@wawancaraEdit')->name('wawancara.edit');
                        Route::put('wawancara/{personil}/update', 'Tad\PersonilUnemployedController@wawancaraUpdate')->name('wawancara.update');
                        Route::delete('wawancara/{personil}/delete', 'Tad\PersonilUnemployedController@wawancaraDelete')->name('wawancara.delete');

                        Route::post('wawancara-hc/{personil}/grid', 'Tad\PersonilUnemployedController@wawancaraHcGrid')->name('wawancara-hc.grid');

                        Route::get('kandidat/{kandidat}/edit', 'Tad\PersonilUnemployedController@kandidatEdit')->name('kandidat.edit');
                        Route::put('kandidat/{kandidat}', 'Tad\PersonilUnemployedController@kandidatUpdate')->name('kandidat.update');
                        Route::get('kandidat/wawancara/{kandidat}', 'Tad\PersonilUnemployedController@wawancaraHcCreate')->name('kandidat.wawancara.create');
                        Route::post('kandidat/wawancara/{kandidat}', 'Tad\PersonilUnemployedController@wawancaraHcStore')->name('kandidat.wawancara.store');
                        Route::get('kandidat/wawancara/{id}/show', 'Tad\PersonilUnemployedController@wawancaraHcShow')->name('kandidat.wawancara.show');
                        Route::get('kandidat/wawancara/{wawancara}/edit', 'Tad\PersonilUnemployedController@wawancaraHcEdit')->name('kandidat.wawancara.edit');
                        Route::put('kandidat/wawancara/{wawancara}/update', 'Tad\PersonilUnemployedController@wawancaraHcUpdate')->name('kandidat.wawancara.update');
                        Route::delete('kandidat/wawancara/{wawancara}', 'Tad\PersonilUnemployedController@wawancaraHcDelete')->name('kandidat.wawancara.delete');

                        Route::get('kandidat/penerimaan/{kandidat}', 'Tad\PersonilUnemployedController@penerimaanCreate')->name('kandidat.penerimaan.create');
                        Route::post('kandidat/penerimaan/{kandidat}', 'Tad\PersonilUnemployedController@penerimaanStore')->name('kandidat.penerimaan.store');
                        Route::post('kandidat/penolakan/{kandidat}', 'Tad\PersonilUnemployedController@penolakanStore')->name('kandidat.penolakan.store');
                    }
                );

            // Personil Belum Bekerja
            Route::resource('gagal', 'Tad\PersonilFailedController');
            // Personil TAD
            Route::prefix('gagal')
                ->name('gagal.')
                ->group(
                    function () {
                        Route::get('import', 'Tad\PersonilFailedController@import')->name('import');
                        Route::post('importSave', 'Tad\PersonilFailedController@importSave')->name('importSave');
                        Route::post('grid', 'Tad\PersonilFailedController@grid')->name('grid');
                        Route::post('kota', 'Tad\PersonilFailedController@kota')->name('kota');
                        Route::post('jurusan', 'Tad\PersonilFailedController@jurusan')->name('jurusan');
                        Route::post('{id}/riwayat-kepegawaian-grid', 'Tad\PersonilFailedController@riwayatKepegawaianGrid')->name('riwayat-kepegawaian.grid');
                        Route::post('{id}/reward-grid', 'Tad\PersonilFailedController@riwayatRewardGrid')->name('reward.grid');

                        Route::post('keluarga/{personil}/grid', 'Tad\PersonilFailedController@keluargaGrid')->name('keluarga.grid');
                        Route::get('keluarga/{personil}/show', 'Tad\PersonilFailedController@keluargaShow')->name('keluarga.show');
                        Route::get('keluarga/{personil}/detailCreate', 'Tad\PersonilFailedController@keluargaDetailCreate')->name('keluarga.detailCreate');
                        Route::get('keluarga/{keluarga}/detailEdit', 'Tad\PersonilFailedController@keluargaDetailEdit')->name('keluarga.detailEdit');
                        Route::get('keluarga/{keluarga}/detailShow', 'Tad\PersonilFailedController@keluargaDetailShow')->name('keluarga.detailShow');
                        Route::get('keluarga/{personil}', 'Tad\PersonilFailedController@keluarga')->name('keluarga');
                        Route::post('keluarga/{personil}', 'Tad\PersonilFailedController@keluargaStore')->name('keluarga.store');
                        Route::post('keluarga/{id}/detailUpdate', 'Tad\PersonilFailedController@keluargaDetailUpdate')->name('keluarga.detailUpdate');
                        Route::put('keluarga/{personil}', 'Tad\PersonilFailedController@keluargaUpdate')->name('keluarga.update');
                        Route::delete('keluarga/id/{keluarga}', 'Tad\PersonilFailedController@keluargaDestroy')->name('keluarga.destroy');

                        // riwayat kerja
                        Route::get('riwayatKerja/{personil}', 'Tad\PersonilFailedController@riwayatKerja')->name('riwayatKerja');
                        Route::post('riwayatKerja/{personil}/grid', 'Tad\PersonilFailedController@riwayatKerjaGrid')->name('riwayatKerja.grid');
                        Route::get('riwayatKerja/{personil}/create', 'Tad\PersonilFailedController@riwayatKerjaCreate')->name('riwayatKerja.create');
                        Route::post('riwayatKerja/{personil}', 'Tad\PersonilFailedController@riwayatKerjaStore')->name('riwayatKerja.store');
                        Route::get('riwayatKerja/{id}/edit', 'Tad\PersonilFailedController@riwayatKerjaEdit')->name('riwayatKerja.edit');
                        Route::post('riwayatKerja/{id}/update', 'Tad\PersonilFailedController@riwayatKerjaUpdate')->name('riwayatKerja.update');
                        Route::get('riwayatKerja/{id}/show', 'Tad\PersonilFailedController@riwayatKerjaShow')->name('riwayatKerja.show');
                        Route::delete('riwayatKerja/{id}/destroy', 'Tad\PersonilFailedController@riwayatKerjaDestroy')->name('riwayatKerja.destroy');

                        Route::post('wawancara/{personil}/grid', 'Tad\PersonilFailedController@wawancaraGrid')->name('wawancara.grid');
                        Route::get('wawancara/{personil}/create', 'Tad\PersonilFailedController@wawancaraCreate')->name('wawancara.create');
                        Route::post('wawancara/{personil}/store', 'Tad\PersonilFailedController@wawancaraStore')->name('wawancara.store');
                        Route::get('wawancara/{wawancara}/show', 'Tad\PersonilFailedController@wawancaraShow')->name('wawancara.show');
                        Route::get('wawancara/{wawancara}/edit', 'Tad\PersonilFailedController@wawancaraEdit')->name('wawancara.edit');
                        Route::put('wawancara/{personil}/update', 'Tad\PersonilFailedController@wawancaraUpdate')->name('wawancara.update');
                        Route::delete('wawancara/{personil}/delete', 'Tad\PersonilFailedController@wawancaraDelete')->name('wawancara.delete');

                        Route::post('wawancara-hc/{personil}/grid', 'Tad\PersonilFailedController@wawancaraHcGrid')->name('wawancara-hc.grid');

                        Route::get('kandidat/{kandidat}/edit', 'Tad\PersonilFailedController@kandidatEdit')->name('kandidat.edit');
                        Route::put('kandidat/{kandidat}', 'Tad\PersonilFailedController@kandidatUpdate')->name('kandidat.update');
                        Route::get('kandidat/wawancara/{kandidat}', 'Tad\PersonilFailedController@wawancaraHcCreate')->name('kandidat.wawancara.create');
                        Route::post('kandidat/wawancara/{kandidat}', 'Tad\PersonilFailedController@wawancaraHcStore')->name('kandidat.wawancara.store');
                        Route::get('kandidat/wawancara/{id}/show', 'Tad\PersonilFailedController@wawancaraHcShow')->name('kandidat.wawancara.show');
                        Route::get('kandidat/wawancara/{wawancara}/edit', 'Tad\PersonilFailedController@wawancaraHcEdit')->name('kandidat.wawancara.edit');
                        Route::put('kandidat/wawancara/{wawancara}/update', 'Tad\PersonilFailedController@wawancaraHcUpdate')->name('kandidat.wawancara.update');
                        Route::delete('kandidat/wawancara/{wawancara}', 'Tad\PersonilFailedController@wawancaraHcDelete')->name('kandidat.wawancara.delete');

                        Route::get('kandidat/penerimaan/{kandidat}', 'Tad\PersonilFailedController@penerimaanCreate')->name('kandidat.penerimaan.create');
                        Route::post('kandidat/penerimaan/{kandidat}', 'Tad\PersonilFailedController@penerimaanStore')->name('kandidat.penerimaan.store');
                        Route::post('kandidat/penolakan/{kandidat}', 'Tad\PersonilFailedController@penolakanStore')->name('kandidat.penolakan.store');
                    }
                );


            // Quota  TAD
            Route::get('quota/edit-detail/{id}', 'Tad\QuotaController@editQuota')->name('quota.detail.edit-quota');
            Route::put('quota/detail/{id}/update', 'Tad\QuotaController@updateQuota')->name('quota.detail.update-quota');
            Route::delete('quota/detail/{id}', 'Tad\QuotaController@destroyQuota')->name('quota.detail.delete-quota');
            Route::get('quota/{id}/detail', 'Tad\QuotaController@detail')->name('quota.detail.index');
            Route::delete('quota/{id}', 'Tad\QuotaController@destroy')->name('quota.destroy');
            Route::get('quota/{id}/riwayat', 'Tad\QuotaController@riwayat')->name('quota.riwayat');
            Route::get('quota/{id}/tracking', 'Tad\QuotaController@tracking')->name('quota.tracking');
            Route::get('quota/{id}/detail/create', 'Tad\QuotaController@createQuota')->name('quota.detail.create-quota');
            Route::post('quota/{id}/detail/store', 'Tad\QuotaController@storeQuota')->name('quota.detail.store-quota');
            Route::post('quota/{id}/detail/grid', 'Tad\QuotaController@gridQuota')->name('quota.detail.grid');
            Route::get('quota/count-available', 'Tad\QuotaController@countAvailable')->name('quota.countAvailable');
            Route::get('quota/{id}/getUpgrade', 'Tad\QuotaController@getUpgrade')->name('quota.getUpgrade');
            Route::post('quota/{id}/saveUpgrade', 'Tad\QuotaController@saveUpgrade')->name('quota.saveUpgrade');
            Route::post('quota/grid', 'Tad\QuotaController@grid')->name('quota.grid');
            Route::get('quota/{id}/approval', 'Tad\QuotaController@approval')->name('quota.approval');
            Route::post('quota/{id}/approval-save', 'Tad\QuotaController@approvalSave')->name('quota.approvalSave');
            Route::resource('quota', 'Tad\QuotaController');
        }
    );
