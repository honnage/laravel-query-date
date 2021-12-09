<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    // public function index()
    // {
    //     // //  แบบที่ 1 
    //     $select = DB::table('transactions')
    //         ->select(
    //             'refNumber',
    //             'machineId',
    //             DB::raw('MIN(transactions.createdAt) AS startDate'),
    //             DB::raw('MAX(transactions.createdAt) AS lastDate'),
    //             DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
    //             DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
    //             DB::raw("DATEDIFF('2021-08-31 23:59:59', MAX(transactions.createdAt)) AS dataDiff"),
    //             DB::raw("CASE WHEN DATEDIFF( '2021-08-31 23:59:59',  MAX(transactions.createdAt)) <= 60 THEN 'Active' ELSE 'cancel serve' END status"),
    //             // DB::raw("CASE 
    //             //     WHEN DATEDIFF( '2021-08-31 23:59:59',  MAX(transactions.createdAt)) >= 120  THEN '9' 
    //             //     WHEN DATEDIFF( '2021-08-31 23:59:59',  MAX(transactions.createdAt)) >= 90 THEN '10'
    //             //     WHEN DATEDIFF( '2021-08-31 23:59:59',  MAX(transactions.createdAt)) >= 60 THEN '11'
    //             //     ELSE 'no cancel' END cancel "),
    //             DB::raw("CASE WHEN DATEDIFF( '2021-08-31 23:59:59',  MAX(transactions.createdAt)) >= 60 THEN '9' ELSE 'no cancel' END cancel ")
    //         )
    //         // ->where('refNumber', '0610282417')
    //         ->where('machineId', '18')
    //         ->whereBetween('createdAt', ['2019-01-01 00:00:00', '2021-08-31 23:59:59'])
    //         ->groupBy('refNumber', 'machineId')
    //         // ->orderBy('machineId')
    //         ->orderBy('startDate')
    //         ->paginate(2000);

    //     // dd($select);
    //     $transactions = $select;
    //     return view('transactions.index', compact('transactions'));
    //     // return view('transactions.index');
    // }


    public function branch($branch)
    {
        $transactions = DB::table('transactions')
            ->select(
                'refNumber',
                'machineId',
                DB::raw('MIN(transactions.createdAt) AS startDate'),
                DB::raw('MAX(transactions.createdAt) AS lastDate'),
                DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
                DB::raw("DATEDIFF('2021-08-31 23:59:59', MAX(transactions.createdAt)) AS dataDiff"),
                DB::raw("CASE WHEN DATEDIFF( '2021-08-31 23:59:59',  MAX(transactions.createdAt)) <= 60 THEN 'Active' ELSE 'cancel serve' END status"),
                DB::raw("CASE WHEN DATEDIFF( '2021-08-31 23:59:59',  MAX(transactions.createdAt)) >= 60 THEN 'deprecated' ELSE 'use work' END cancel "),
                DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = 8  AND MIN(YEAR(transactions.createdAt)) = 2021 THEN 'new user' ELSE 'order user' END statusUser ")
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', ['2019-01-01 00:00:00', '2021-08-31 23:59:59'])
            ->groupBy('refNumber', 'machineId')
            ->orderBy('startDate')
            ->paginate(2000);


        $newUser = DB::table('transactions')
            ->select(
                'refNumber',
                'machineId',
                DB::raw('MIN(transactions.createdAt) AS startDate'),
                DB::raw('MAX(transactions.createdAt) AS lastDate'),
                DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate')
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', ['2019-01-01 00:00:00', '2021-08-31 23:59:59'])
            ->groupBy('refNumber', 'machineId')
            ->havingBetween('startDate', ['2021-08-01 00:00:00', '2021-08-31 23:59:59'] )
            ->orderBy('startDate')
            ->get();

        $countNewUser =  $newUser->count();

        return view('transactions.index', compact('transactions', 'countNewUser'));
    }
}
