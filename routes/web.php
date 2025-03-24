<?php

use App\Http\Controllers\CsvController;
use Illuminate\Support\Facades\Route;

Route::get('/csv', [CsvController::class, 'index'])->name('csv.index');
Route::post('/csv/import', [CsvController::class, 'import'])->name('csv.import');
Route::get('/csv/export', [CsvController::class, 'export'])->name('csv.export');
