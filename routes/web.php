<?php

use App\Http\Controllers\ClassesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TransactionController;
use App\Models\User;
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

Route::get('/dashboard', [TransactionController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Route::get('checker', function(){
//     return response()->json(
//         User::first()
//     );
// });

Route::middleware(['auth', 'user_type:teacher,admin'])->group(function () {
    // Route::get('/users', [UserController::class, 'index'])->name('users');
    // Route::post('/users', [UserController::class, 'store'])->name('addUsers');

    Route::get('transact', [TransactionController::class, 'create'])->name('transact');
    Route::post('transact', [TransactionController::class, 'store']);
    Route::post('transactions/delete', [TransactionController::class, 'delete'])->name('transactions.delete');
    Route::post('transactions', [TransactionController::class, 'index'])->name('transactions');






    // //PARENTS
    // Route::get('/parents', [UserController::class, 'parents'])->name('parents');
    // Route::post('/parents/edit', [UserController::class, 'editParent'])->name('parents.edit');

    // // STUDENTS
    // Route::get('/students', [StudentController::class, 'index'])->name('students');
    // Route::post('/students/edit', [StudentController::class, 'edit'])->name('students.edit');

    // // TEACHERS
    // Route::get('/{type}', [UserController::class, 'users'])->name('users');
    // Route::post('/{type}/edit', [UserController::class, 'editUser'])->name('users.edit');
});

Route::middleware(['auth', 'user_type:admin'])->group(function () {

    Route::post('ajax_students', [StudentController::class, 'ajaxStudents'])
                        ->middleware(['ajax_only'])
                        ->name('ajax.students');
    // Route::post('students_delete_all', [StudentController::class, 'truncate'])
    //                     ->middleware(['no_ajax', 'password.confirm'])
    //                     ->name('ajax.students.delete_all');

    Route::get('export_backup', [StudentController::class, 'export'])->name('export_backup');
    // Route::get('artisan', [UserController::class, 'artisan'])
    //                     ->middleware(['no_ajax', 'password.confirm'])
    //                     ->name('migrate.refresh');

    Route::get('classes', [ClassesController::class, 'index'])->name('classes');
    Route::post('classes/edit', [ClassesController::class, 'editClass'])->name('classes.edit');

    // TEACHERS
    Route::get('/{type}', [UserController::class, 'users'])->name('users');
    Route::post('/{type}/edit', [UserController::class, 'editUser'])->name('users.edit');
    Route::get('teacher/{teacher}', [UserController::class, 'teacher'])->name('teacher');
});
