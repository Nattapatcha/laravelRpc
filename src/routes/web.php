<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PesticidesController;
use App\Http\Controllers\DiseaseInsectsController;
use App\Http\Controllers\IssuesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\AgriculturistsController;
use App\Http\Controllers\FilesController;

Route::get('/', function () {
    return redirect(url('/login'));
});

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm']);
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::get('/register', [AuthController::class, 'getRegister']);
Route::post('/register', [AuthController::class, 'postRegister']);

Route::get('/employees',      [EmployeesController::class, 'index'])->name('employees.index');
Route::get('/agriculturists', [AgriculturistsController::class, 'index'])->name('agriculturists.index');
// File Routes (public access)
Route::get('files/images/{file}', [FilesController::class, 'images'])->name('files.images');
Route::get('files/download/{file}', [FilesController::class, 'download'])->name('files.download');

// Protected Routes (middleware auth)
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [PesticidesController::class, 'index']);

    // Pesticides Routes
    Route::get('pesticides', [PesticidesController::class, 'index'])->name('pesticides.index');
    Route::get('pesticides/add', [PesticidesController::class, 'add'])->name('pesticides.add');         // ฟอร์มเพิ่มข้อมูล (GET)
    Route::post('pesticides/add', [PesticidesController::class, 'store'])->name('pesticides.store');          // รับข้อมูลเพิ่ม (POST)
    Route::get('pesticides/{id}/edit', [PesticidesController::class, 'edit'])->name('pesticides.edit');
    Route::put('pesticides/{id}', [PesticidesController::class, 'update'])->name('pesticides.update');
    Route::delete('pesticides/{id}', [PesticidesController::class, 'destroy'])->name('pesticides.destroy');    // ลบ (DELETE)

    // Disease Insects Routes
    Route::get('disease_insects', [DiseaseInsectsController::class, 'index'])->name('disease_insects.index');
    Route::get('disease_insects/add', [DiseaseInsectsController::class, 'add'])->name('disease_insects.add');
    Route::post('disease_insects/add', [DiseaseInsectsController::class, 'store'])->name('disease_insects.store');
    Route::get('disease_insects/{id}/edit', [DiseaseInsectsController::class, 'edit'])->name('disease_insects.edit');
    Route::put('disease_insects/{id}', [DiseaseInsectsController::class, 'update'])->name('disease_insects.update');
    Route::delete('disease_insects/{id}', [DiseaseInsectsController::class, 'destroy'])->name('disease_insects.destroy');

    // Issues Routes
    Route::get('issues', [IssuesController::class, 'index'])->name('issues.index');
    Route::get('issues/{id}', [IssuesController::class, 'show'])->name('issues.show');
    Route::delete('issues/{id}', [IssuesController::class, 'destroy'])->name('issues.destroy');
    Route::post('issues/{id}/reply', [IssuesController::class, 'reply'])->name('issues.reply');

});