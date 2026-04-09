<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\DvdController;


Route::get('/', function () {
    return redirect('/login');
});

// Routes d'authentification
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Page d'accueil
Route::get('/accueil', function () {
    return view('accueil');
})->middleware('auth')->name('accueil');

// Routes pour les livres
Route::get('/livres', [LivreController::class, 'livres'])
    ->middleware('auth')
    ->name('livres');

Route::post('/livres/update/{id}', [LivreController::class, 'updateLivre'])
    ->middleware('auth')
    ->name('livres.update');

Route::delete('/livres/{id}', [LivreController::class, 'destroy'])
    ->middleware('auth')
    ->name('livres.destroy');

Route::post('/livres', [LivreController::class, 'store'])
    ->middleware('auth')
    ->name('livres.store');


Route::middleware('auth')->group(function () {
    Route::get('/dvds', [DvdController::class, 'index'])->name('dvds.index');
    Route::post('/dvds', [DvdController::class, 'store'])->name('dvds.store');
    Route::put('/dvds/{id}', [DvdController::class, 'update'])->name('dvds.update');
    Route::delete('/dvds/{id}', [DvdController::class, 'destroy'])->name('dvds.destroy');
});
