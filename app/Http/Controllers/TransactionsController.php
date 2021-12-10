<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionsController extends Controller
{
    public function historyReports(Request $request, $branch)
    {
        $dateTime_Start = '2019-01-01 00:00:00';
        $dateTime_Last = '2021-08-31 23:59:59';
        $monthSelected = 8;
        $yearSelected = 2021;
        $provisoDay = 60;
        // $informationWhen = "2021/9";

        $selected = DB::table('transactions')
            ->select(
                'refNumber',
                'machineId',
                DB::raw('MIN(transactions.createdAt) AS startDate'),
                DB::raw('MAX(transactions.createdAt) AS lastDate'),
                DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
                DB::raw("DATEDIFF('$dateTime_Last', MAX(transactions.createdAt)) AS dataDiff"),
                DB::raw("CASE WHEN DATEDIFF( '$dateTime_Last',  MAX(transactions.createdAt)) <= $provisoDay 
                    THEN 'Active' 
                    ELSE 'Deprecated' 
                    END statusActive"),
                DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = $monthSelected AND MIN(YEAR(transactions.createdAt)) = $yearSelected
                    THEN 'new user' 
                    ELSE 'old user' 
                    END statusUser")
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
            ->groupBy('refNumber', 'machineId')
            ->orderBy('startDate')
            ->get();

        foreach ($selected as $data) {
            $newData = array(
                "phone" => $data->refNumber,
                "branch" => $data->machineId,
                "startDate" => $data->startDate,
                "lastDate" => $data->lastDate,
                "countTrans" => $data->countTrans,
                "countDate" => $data->countDate,
                "dataDiff" => $data->dataDiff,
                "statusActive" => $data->statusActive,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            );

            $select_HistoryReports = DB::table('history_reports')
                ->select(
                    'phone',
                    'branch',
                    'startDate',
                    'lastDate',
                    'countTrans',
                    'countDate',
                    'dataDiff',
                    'statusActive',
                )
                ->where('phone', $data->refNumber)
                ->where('branch', $data->machineId)
                ->where('lastDate', $data->lastDate)
                ->orderBy('startDate')
                ->get();

            if (count($select_HistoryReports) == 0)
                $history_reports = DB::table('history_reports')->insert($newData);
            else {
                dd(count($select_HistoryReports));
            }
            // { UPDATE }
        }
    }


    public function branch($branch)
    {
        $dateTime_Start = '2019-01-01 00:00:00';
        $dateTime_Last = '2021-08-31 23:59:59';
        $monthSelected = 8;
        $yearSelected = 2021;
        $provisoDay = 60;

        $transactions = DB::table('transactions')
            ->select(
                'refNumber',
                'machineId',
                DB::raw('MIN(transactions.createdAt) AS startDate'),
                DB::raw('MAX(transactions.createdAt) AS lastDate'),
                DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
                DB::raw("DATEDIFF('$dateTime_Last', MAX(transactions.createdAt)) AS dataDiff"),
                DB::raw("CASE WHEN DATEDIFF( '$dateTime_Last', MAX(transactions.createdAt)) <= $provisoDay
                    THEN 'Active' 
                    ELSE 'Deprecated' 
                    END statusActive"),
                DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = $monthSelected AND MIN(YEAR(transactions.createdAt)) = $yearSelected 
                    THEN 'new user' 
                    ELSE 'old user' 
                    END statusUser ")
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
            ->groupBy('refNumber', 'machineId')
            ->orderBy('startDate')
            ->paginate(500);

        // dd( $dateTime_Last);

        $users = DB::table('transactions')
            ->select(
                'refNumber',
                'machineId',
                DB::raw('MIN(transactions.createdAt) AS startDate'),
                DB::raw('MAX(transactions.createdAt) AS lastDate'),
                DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
                DB::raw("DATEDIFF('$dateTime_Last', MAX(transactions.createdAt)) AS dataDiff"),
                DB::raw("CASE WHEN DATEDIFF( '$dateTime_Last',  MAX(transactions.createdAt)) <= $provisoDay 
                    THEN 'Active' 
                    ELSE 'Deprecated' 
                    END statusActive"),
                DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = $monthSelected AND MIN(YEAR(transactions.createdAt)) = $yearSelected  
                    THEN 'new user' 
                    ELSE 'old user'
                    END statusUser ")
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
            ->groupBy('refNumber', 'machineId')
            ->orderBy('startDate')
            ->get();

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
            ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
            ->groupBy('refNumber', 'machineId')
            ->havingBetween('startDate', [$dateTime_Start, $dateTime_Last])
            ->orderBy('startDate')
            ->get();

        $activeUser = DB::table('transactions')
            ->select(
                'refNumber',
                'machineId',
                DB::raw('MIN(transactions.createdAt) AS startDate'),
                DB::raw('MAX(transactions.createdAt) AS lastDate'),
                DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
                DB::raw("DATEDIFF('2$dateTime_Last', MAX(transactions.createdAt)) AS dataDiff"),
                DB::raw("CASE WHEN DATEDIFF( '$dateTime_Last',  MAX(transactions.createdAt)) <= $provisoDay 
                    THEN 'Active' 
                    ELSE 'Deprecated' 
                    END statusActive"),
                DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = $monthSelected  AND MIN(YEAR(transactions.createdAt)) = $yearSelected  
                    THEN 'new user' 
                    ELSE 'old user' 
                    END statusUser ")
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
            ->groupBy('refNumber', 'machineId')
            ->having('statusActive', 'Active')
            ->orderBy('startDate')
            ->get();

        $deprecatedUser = DB::table('transactions')
            ->select(
                'refNumber',
                'machineId',
                DB::raw('MIN(transactions.createdAt) AS startDate'),
                DB::raw('MAX(transactions.createdAt) AS lastDate'),
                DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
                DB::raw("DATEDIFF('$dateTime_Last', MAX(transactions.createdAt)) AS dataDiff"),
                DB::raw("CASE WHEN DATEDIFF( '$dateTime_Last',  MAX(transactions.createdAt)) <= $provisoDay 
                    THEN 'Active' 
                    ELSE 'Deprecated' 
                    END statusActive"),
                DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = $monthSelected  AND MIN(YEAR(transactions.createdAt)) = $yearSelected  
                    THEN 'new user' 
                    ELSE 'old user' 
                    END statusUser ")
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
            ->groupBy('refNumber', 'machineId')
            ->having('statusActive', 'deprecated')
            ->orderBy('startDate')
            ->get();

        $countUser = $users->count();
        $countNewUser =  $newUser->count();
        $countActiveUser = $activeUser->count();
        $countDeprecatedUser = $deprecatedUser->count();
        return view('transactions.index', compact('transactions', 'countNewUser', 'countUser', 'countActiveUser', 'countDeprecatedUser', 'branch'));
    }
}
