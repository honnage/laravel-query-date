<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\TransactionsBetweenController;
use App\Http\Controllers\QueryTransactionController;
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

Route::get('/queryTransactions/branch:{branch}/year:{year}/month:{month}',[QueryTransactionController::class, 'query'])->name('transactions.query');


Route::get('/transactions/branch:{branch}/year:{year}/month:{month}',[TransactionsController::class, 'branch'])->name('transactions.branch');
Route::post('/transactions/store/branch:{branch}/year:{year}/month:{month}',[TransactionsController::class,'historyReports'])->name('historyReports');


Route::get('/transactions/branch:{branch}/year:{yearStrat}/month:{monthStart}/to/year:{yearEnd}/month:{monthEnd}',[TransactionsBetweenController::class, 'branchBetween'])->name('transactions.branch');
Route::post('/transactions/store/branch:{branch}/year:{yearStrat}/month:{monthStart}/to/year:{yearEnd}/month:{monthEnd}',[TransactionsBetweenController::class, 'historyReportsBetween']);
// http://127.0.0.1:8000/transactions/store/branch:18/year2019/month:07/to/year2019/month:09
