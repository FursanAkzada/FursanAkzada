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

Route::prefix('settings')
    ->name('settings.')
    ->middleware('auth')
    ->group(
        function () {

            Route::prefix('profiles')
                ->namespace('Profiles')
                ->group(
                    function () {
                        Route::resource('profile', ProfileController::class)->only(['index', 'store']);
                        Route::get('profile/photo', 'ProfileController@photoIndex')->name('profile.photo');
                        Route::post('profile/photo', 'ProfileController@photoStore')->name('profile.photo.store');

                        Route::post('notification/grid', 'NotificationController@grid')->name('notification.grid');
                        Route::resource('notification', NotificationController::class)->only(['index']);

                        Route::post('activity/grid', 'ActivityController@grid')->name('activity.grid');
                        Route::resource('activity', ActivityController::class)->only(['index', 'show']);

                        Route::resource('change-password', ChangePasswordController::class)->only(['index', 'store']);
                    }
                );

            Route::post('user/ajax', 'UserController@selectAjax')->name('user.ajax');
            Route::get('user/{id}/getUpgrade', 'UserController@getUpgrade')->name('user.getUpgrade');
            Route::post('user/{id}/saveUpgrade', 'UserController@saveUpgrade')->name('user.saveUpgrade');
            // Route::get('user/{record}/resetPassword', 'UserController@resetPassword')->name('user.resetPassword');
            Route::post('user/ajaxUserDivisiHC', 'UserController@selectAjaxUserDivisiHC')->name('user.ajax.selectAjaxUserDivisiHC');
            Route::post('user/grid', 'UserController@grid')->name('user.grid');
            Route::resource('user', UserController::class);

            Route::post('user-vendor/grid', 'UserVendorController@grid')->name('user-vendor.grid');
            Route::resource('user-vendor', UserVendorController::class);
            Route::get('user-vendor/{id}/getUpgrade', 'UserVendorController@getUpgrade')->name('user-vendor.getUpgrade');
            Route::post('user-vendor/{id}/saveUpgrade', 'UserVendorController@saveUpgrade')->name('user-vendor.saveUpgrade');

            Route::post('roles/grid', 'RolesController@grid')->name('roles.grid');
            Route::post('roles/grant/{group}', 'RolesController@grant')->name('roles.grant');
            Route::post('roles/{search}/selectRole', 'RolesController@selectRole')->name('roles.selectRole');
            Route::resource(
                'roles',
                RolesController::class
            )
                ->parameters(
                    [
                        'roles' => 'group'
                    ]
                );

            Route::post('flow/grid', 'FlowController@grid')->name('flow.grid');
            Route::resource('flow', 'FlowController');
            Route::get('flow/{id}/riwayat', 'FlowController@riwayat')->name('flow.riwayat');

            Route::post('audit-trail/grid', 'AuditTrailController@grid')->name('audit-trail.grid');
            Route::resource('audit-trail', AuditTrailController::class);
        }
    );
