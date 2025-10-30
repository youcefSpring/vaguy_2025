<?php

use Illuminate\Support\Facades\Route;

Route::get('/{locale}/test-translations', function($locale) {
    return view('test-translations-debug', compact('locale'));
})->where('locale', 'en|fr|ar');
