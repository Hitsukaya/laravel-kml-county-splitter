<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\KmlUploader;
use App\Livewire\MultiKmlUploader;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/kml-uploader', function () {
    return view('kml-uploader');
})->name('kml-uploader');

Route::get('/multi-kml-uploader', function () {
    return view('multi-kml-uploader');
})->name('multi-kml-uploader');

