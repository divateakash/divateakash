<?php

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

Route::any('providers/{provider}/webhook', 'Providers\WebhookController')->name('providers.webhook');
Route::post('providers/{provider}/dlr', 'Providers\DeliveryReportsController')->name('providers.delivery_report');

Route::post('lists/{catalog}/leads', 'Api\LeadsController@store')->name('leads.store');
