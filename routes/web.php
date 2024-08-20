<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('tasks', TaskController::class)->middleware('auth');
Route::get('tasks',[TaskController::class,'edit'])->name('tasks.edit');
Route::get('tasks',[TaskController::class,'index'])->name('tasks.index');


// Route pour afficher le formulaire de profil
Route::get('/profile', [ProfileController::class, 'showForm']);

// Route pour traiter le téléchargement de la photo
Route::get('/profile/upload', [ProfileController::class, 'uploadPhoto'])->name('profile.upload');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
