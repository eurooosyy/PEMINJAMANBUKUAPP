<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;

Route::middleware('auth')->group(function () {
    Route::resource('equipment', EquipmentController::class);
});
