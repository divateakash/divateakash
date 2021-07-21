<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('campaigns/schedule', 'CampaignsController@scCampaign');
Route::middleware('auth')->group(function () {
    Route::resource('settings', 'SettingsController');

    Route::get('dashboard', 'DashboardController')->name('dashboard.index');
    Route::resource('campaigns', 'CampaignsController');
    Route::get('campaigns/{campaign}/outbounds', 'CampaignsController@outbounds')->name('campaigns.outbounds.index');
    Route::get('campaigns/{campaign}/replies', 'CampaignsController@replies')->name('campaigns.replies.index');
    Route::get('campaigns/{campaign}/pending-replies', 'CampaignsController@pendingReplies')->name('campaigns.pending-replies.index');
    Route::post('campaigns/{campaign}/pending-replies', 'CampaignsController@retryPendingReplies')->name('campaigns.pending-replies.retry');
    Route::post('campaigns/{campaign}/scramble-pending-replies', 'CampaignsController@scramblePendingReplies')->name('campaigns.pending-replies.scramble');

    // drips
    Route::resource('drips', 'DripsController');

    Route::resource('providers', 'ProvidersController');

    Route::post('accounts/{account}/sub_accounts', 'AccountsController@attachSubaccount')->name('accounts.sub_accounts.store');
    Route::delete('accounts/{account}/sub_accounts/{subAccount}', 'AccountsController@detachSubaccount')->name('accounts.sub_accounts.delete');
    Route::resource('accounts', 'AccountsController')->only('index', 'show', 'create', 'store');
    Route::resource('numbers', 'NumbersController')->only('index', 'show', 'create', 'store');
// Route::post('accounts/{account}/numbers', 'AccountsController@storeNumbers')->name('accounts.numbers.store');

    Route::resource('message-groups', 'MessageGroupsController');
    Route::get('message-groups/{messageGroup}/messages/create', 'MessageGroupsController@showCreateMessageForm')->name('message-groups.messages.create');
    Route::resource('messages', 'MessagesController');

    Route::resource('offers', 'OffersController')->only('index', 'show', 'create', 'store');

    Route::resource('lists', 'ListsController')->only('index', 'show', 'create', 'store');

    Route::resource('file-uploads', 'FileUploadsController')->only('show', 'store');
    Route::post('file-uploads/{fileUpload}/process', 'FileUploadsController@process')->name('file_uploads.process');

    Route::resource('fam_leads', 'FamLeadsController')->only('index', 'store');
    Route::get('fam_leads/{file}/download', 'FamLeadsController@download')->name('fam_leads.download');

    // Domains..
    Route::resource('domain-providers', 'DomainProvidersController');
    Route::resource('domain-groups', 'DomainGroupsController');
    Route::post('domain-groups/{domainGroup}/domains', 'DomainGroupsController@storeDomains')->name('domain_groups.domains.store');

    // Suppressions
    Route::resource('suppressions', 'SuppressionsController')->only(['index', 'show', 'destroy']);
    Route::post('suppressions/file', 'SuppressionsController@file')->name('suppressions.file.store');

    Route::resource('reply-words', 'ReplyWordsController')->only(['index']);
});

Auth::routes();

Route::fallback(fn() => redirect()->route('dashboard.index'));

// Route::get('/home', 'HomeController@index')->name('home');
