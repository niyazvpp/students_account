<?php

use App\Http\Controllers\ClassesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware(['auth']);

Route::get('/dashboard', function () {
    return view('dashboard', ['header' => 'Dashboard', 'desc' => 'You can view all the primary data here']);
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    // Route::get('/users', [UserController::class, 'index'])->name('users');
    // Route::post('/users', [UserController::class, 'store'])->name('addUsers');
    
    Route::get('classes', [ClassesController::class, 'index'])->name('classes');
    Route::post('classes/edit', [ClassesController::class, 'editClass'])->name('classes.edit');

    // TEACHERS & PARENTS
    Route::get('/parents', [UserController::class, 'parents'])->name('parents');
    Route::post('/parents/edit', [UserController::class, 'editParent'])->name('parents.edit');
    Route::get('/{type}', [UserController::class, 'users'])->name('users');
    Route::post('/{type}/edit', [UserController::class, 'editUser'])->name('users.edit');
});