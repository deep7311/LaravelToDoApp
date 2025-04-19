<?php

use App\Http\Controllers\NotePadController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view('welcome');
});

// Notepad Routes
Route::get("/notepad", [NotePadController::class, 'index'])->name('notepad.create');
Route::post("/notepad/store", [NotePadController::class, 'store'])->name('notepad.store');
Route::put("/notepad/update/{id}", [NotePadController::class, 'update'])->name('notepad.update');  
Route::delete("/notepad/delete/{id}", [NotePadController::class, 'destroy'])->name('notepad.delete'); // Add this line for delete route

