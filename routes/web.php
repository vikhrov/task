<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});




//Route::post('/employees/store', [EmployeeController::class, 'storeAjax'])->name('employees.storeAjax');
Route::get('/employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

Route::get('/get-managers', [EmployeeController::class, 'getManagers'])->name('get-managers');


Route::resource('positions', PositionController::class);
Route::get('/positions/create', [PositionController::class, 'create'])->name('positions.create');
Route::post('/positions/store', [PositionController::class, 'store'])->name('positions.store');
Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit');
Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');

Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');


//Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
//    Route::view('about', 'about')->name('about');
//
//    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
//
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});


Auth::routes();
