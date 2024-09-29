<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\HomeController;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\KepegawaianFailed;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Tad\TadFailed;
use Modules\Pengajuan\Entities\Mutasi\Pegawai as PegawaiMutasi;
use Modules\Pengajuan\Entities\Resign\Pegawai as PegawaiResign;
use Modules\Pengajuan\Entities\Resign\Pengajuan as PengajuanResign;
use Modules\Pengajuan\Entities\Tad\Pengajuan;

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

Route::get(
    '/',
    function () {
        return redirect()->route('login');
    }
);
Route::get(
    '/icons',
    function () {
        return abort(403);
    }
);
Route::get('userNotification/{notification}/read', [AjaxController::class, 'userNotificationRead'])->name('userNotificationRead');
Route::post('ajax/saveTempFiles', [AjaxController::class, 'saveTempFiles'])->name('ajax.saveTempFiles');
Route::get('ajax/getTempFiles', [AjaxController::class, 'getTempFiles'])->name('ajax.getTempFiles');
Route::get('ajax/getQuotaCount', [AjaxController::class, 'getQuotaCount'])->name('ajax.getQuotaCount');
Route::post('ajax/{search}/selectVendor', [AjaxController::class, 'selectVendor'])->name('ajax.selectVendor');
Route::get('maintenance', [HomeController::class, 'getMaintenance'])->name('getMaintenance');


Auth::routes();

Route::get(
    'dev/kepegawaian-null',
    function () {
        $tads = Tad::select('id', 'kepegawaian_id', 'nama')
            ->whereNull('kepegawaian_id')
            ->get();
        dd(
            json_decode($tads),
            json_encode($tads->pluck('nama')),
        );
    }
);
Route::get(
    'dev/reset-migrasi',
    function () {
        foreach (Kepegawaian::get() as $key => $record) {
            try {
                $record->delete();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        foreach (KepegawaianFailed::get() as $key => $record) {
            try {
                $record->delete();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        foreach (Tad::get() as $key => $record) {
            try {
                $record->delete();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        foreach (TadFailed::get() as $key => $record) {
            try {
                $record->delete();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
);

Route::get(
    'dev/push',
    function () {
        Artisan::call('push:personil-mutasi');
        Artisan::call('push:personil-resign');
    }
);

Route::get(
    'dev/aktif',
    function () {
        $payload = [
            'ReqDate'   => now()->format('Y-m-d'),
        ];
        $payload['Signature'] = base64_encode(hash_hmac(
            'sha256',
            $payload['ReqDate'],
            'jatim',
            true
        ));
        $response = Http::withHeaders(
            [
                'Authorization' => 'Basic ' . base64_encode('prgm:pragmainformatika')
            ]
        )
            ->withoutVerifying()
            ->post(
                'https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/GetAktifTAD',
                $payload
            );
        return $response->json();
    }
);
Route::get(
    'dev/nonaktif',
    function () {
        $payload = [
            'ReqDate'   => now()->format('Y-m-d'),
        ];
        $payload['Signature'] = base64_encode(hash_hmac(
            'sha256',
            $payload['ReqDate'],
            'jatim',
            true
        ));
        $response = Http::withHeaders(
            [
                'Authorization' => 'Basic ' . base64_encode('prgm:pragmainformatika')
            ]
        )
            ->withoutVerifying()
            ->post(
                'https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/GetNonAktifTAD',
                $payload
            );
        return $response->json();
    }
);
