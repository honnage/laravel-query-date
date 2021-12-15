<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionsController;
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
    return view('welcome');
});



Route::get('/transactions/all', [TransactionsController::class, 'index'])->name('transactions.index');
// Route::get('/transactions/branch:{branch}', [TransactionsController::class, 'branch'])->name('transactions.branch');
// Route::post('/transactions/store/branch:{branch}',[TransactionsController::class,'historyReports'])->name('historyReports');

Route::get('/transactions/branch:{branch}/year:{year}/month:{month}/day:{day}',[TransactionsController::class, 'branch'])->name('transactions.branch');
Route::post('/transactions/store/branch:{branch}/year:{year}/month:{month}/day:{day}',[TransactionsController::class,'historyReports'])->name('historyReports');