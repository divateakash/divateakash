<?php

use App\Services\DomainRedirectorService;
use Illuminate\Support\Facades\Route;

Route::any('/{any}', DomainRedirectorService::class)->where('any', '.*');

// Route::get('/home', 'HomeController@index')->name('home');
