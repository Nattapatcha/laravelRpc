<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesticidesController;
use App\Http\Controllers\DiseaseInsectsController;
use App\Http\Controllers\IssuesController;


Route::get('pesticides', [PesticidesController::class, 'index'])
    ->name('api.pesticides.index');

Route::get('disease_insects', [DiseaseInsectsController::class, 'index'])
    ->name('api.disease_insects.index');

Route::post('issues', [IssuesController::class, 'storeMultiple'])
    ->name('api.issues.add');

