<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionsController extends Controller
{
    public function historyReports( $branch, $year, $month)
    {
        // for($i = 3; $i <= 6; $i++) {
        //     switch ( $i) { 
        //         case '1':   $daySelected_last = 31; break;
        //         case '2':   $daySelected_last = 28; break;
        //         case '3':   $daySelected_last = 31; break;
        //         case '4':   $daySelected_last = 30; break;
        //         case '5':   $daySelected_last = 31; break;
        //         case '6':   $daySelected_last = 30; break;
        //         case '7':   $daySelected_last = 31; break;
        //         case '8':   $daySelected_last = 31; break;
        //         case '9':   $daySelected_last = 30; break;
        //         case '10':  $daySelected_last = 31; break;
        //         case '11':  $daySelected_last = 30; break;  
        //         case '12':  $daySelected_last = 31; break;
        //         default:    /* code... */  break;
        //     }

        $yearSelected = $year;      // $yearSelected = "2021";
        $monthSelected = $month;    // $monthSelected = "02";
        // $daySelected_last = $day;   // $daySelected_last = 28;
        $daySelected_start = 1;     // $daySelected_start = 1;

        switch ( $month) { 
            case '01':  $daySelected_last = 31; break;
            case '02':  
                if( ($year % 400 ==0) OR ($year % 4 == 0) AND ($year % 100 != 0)){
                    $daySelected_last = 29; 
                }else{
                    $daySelected_last = 28; 
                }
            break;
            case '03':  $daySelected_last = 31; break;
            case '04':  $daySelected_last = 30; break;
            case '05':  $daySelected_last = 31; break;
            case '06':  $daySelected_last = 30; break;
            case '07':  $daySelected_last = 31; break;
            case '08':  $daySelected_last = 31; break;
            case '09':  $daySelected_last = 30; break;
            case '10':  $daySelected_last = 31; break;
            case '11':  $daySelected_last = 30; break;  
            case '12':  $daySelected_last = 31; break;
            default:    /* code... */  break;
        }
        // $day = $daySelected_last;
        // $dateTime_Start = "2019-07-01 00:00:00";
        // $dateTime_Last = "$yearSelected-$monthSelected-$daySelected_last 23:59:59";
        // $dateTime_Select = "$yearSelected-$monthSelected-$daySelected_start 00:00:00";            
        $dateTime_Start = "2019-07-01 00:00:00";
        $dateTime_Last = "$yearSelected-$monthSelected-$daySelected_last 23:59:59";
        // $dateTime_Select = "$yearSelected-$monthSelected-$daySelected_start 00:00:00";
        $dateTime_Summarry = "$yearSelected / $monthSelected";
    

        $selected = DB::table('transactions')
        ->select(
            'refNumber as phone',
            'machineId as branch',
            DB::raw('SUM(transactions.price)  AS amount'),
            DB::raw('MIN(transactions.createdAt) AS startDate'),
            DB::raw('MAX(transactions.createdAt) AS lastDate'),
            DB::raw('COUNT(transactions.refNumber)  AS trans'),
            DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS numberOfDays'),
            // DB::raw("DATEDIFF(MAX(transactions.createdAt, '$dateTime_Last')) AS dataDiff"),
            DB::raw("DATEDIFF( '$dateTime_Last',  MAX(transactions.createdAt)) AS lastDayOfUse"),
            DB::raw("YEAR('$dateTime_Last') AS dataOfYear"),
            DB::raw("MONTH('$dateTime_Last') AS dataOfMonth"),
            DB::raw('TIMESTAMPDIFF(month, min(transactions.createdAt), max(transactions.updatedAt)) AS numberOfMonth '),
            DB::raw("CASE 
                WHEN DATEDIFF( MAX(transactions.updatedAt), '$dateTime_Last' ) < -35 AND COUNT(DISTINCT DATE(transactions.updatedAt)) > 1 AND sum(transactions.price) > 1000 then 'Churn_NeedCheck'
                WHEN DATEDIFF( MAX(transactions.updatedAt), '$dateTime_Last' ) < -35 AND COUNT(DISTINCT DATE(transactions.updatedAt)) > 1 THEN 'Churn'
                WHEN DATEDIFF( MAX(transactions.updatedAt), '$dateTime_Last' ) < -35 AND COUNT(DISTINCT DATE(transactions.updatedAt)) <= 1 THEN 'Churn_1stTimeUse'
                ELSE 'Active' 
                END ActiveStatus"),
            DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = $monthSelected AND MIN(YEAR(transactions.createdAt)) = $yearSelected 
                THEN 'New Customer' 
                ELSE 'Old Customer' 
                END customerStatus "),
            DB::raw("CASE WHEN  MONTH('$dateTime_Last') =  MONTH(MAX(transactions.createdAt)) AND YEAR('$dateTime_Last') = YEAR(MAX(transactions.createdAt)) 
                THEN 'TRUE'
                ELSE 'FALSE'
                END useInMonth ")
                
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
            ->groupBy('refNumber', 'machineId')
            ->orderBy('amount', 'DESC')
            ->get();

            foreach ($selected as $data) {
                $newData = array(
                    "branch" => $data->branch,
                    "trans" => $data->trans,
                    "amount" => $data->amount,
                    "phone" => $data->phone,
                    "startDate" => $data->startDate,
                    "lastDate" => $data->lastDate,
                    "customerStatus" => $data->customerStatus,
                    "numberOfDays" => $data->numberOfDays,
                    "lastDayOfUse" => $data->lastDayOfUse,
                    "numberOfMonth" =>  $data->numberOfMonth,
                    "useInMonth" => $data->useInMonth,
                    "ActiveStatus" =>  $data->ActiveStatus,
                    "dataOfYear" =>  $data->dataOfYear,
                    "dataOfMonth" =>  $data->dataOfMonth,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                    // "summaryDate" =>  $dateTime_Summarry,
                );

                // $select_HistoryReportDetails = DB::table('history_report_details')
                //     ->select(
                //         'branch',
                //         'trans',
                //         'amount',
                //         'phone',
                //         'startDate',
                //         'lastDate',
                //         'customerStatus',
                //         'numberOfDays',
                //         "lastDayOfUse",
                //         'numberOfMonth',
                //         'useInMonth',
                //         'ActiveStatus',
                //         'dataOfYear',
                //         'dataOfMonth',
                //     )
                //     ->where('phone', $data->phone)
                //     ->where('branch', $data->branch)
                //     ->where('lastDate', $data->lastDate)
                //     ->where('dataOfYear', $data->dataOfYear)
                //     ->where('dataOfMonth', $data->dataOfMonth)
                //     // ->where('summaryDate', $dateTime_Summarry)
                //     ->orderBy('startDate')
                //     ->get();


                // if (count($select_HistoryReportDetails) == 0) {
                //     DB::table('history_report_details')->insert($newData);
                // }

                DB::table('history_report_details')->insert($newData);
            }
        return redirect()->back()->with('success', '???????????????????????????????????????????????????????????????');
    }


    public function branch($branch, $year, $month)
    {
        $yearSelected = $year;      // $yearSelected = "2021";
        $monthSelected = $month;    // $monthSelected = "02";
        // $daySelected_last = $day;   // $daySelected_last = 28;
        $daySelected_start = 1;     // $daySelected_start = 1;

        switch ( $month) { 
            case '01':  $daySelected_last = 31; break;
            case '02':  
                if( ($year % 400 ==0) OR ($year % 4 == 0) AND ($year % 100 != 0)){
                    $daySelected_last = 29; 
                }else{
                    $daySelected_last = 28; 
                }
            break;
            case '03':  $daySelected_last = 31; break;
            case '04':  $daySelected_last = 30; break;
            case '05':  $daySelected_last = 31; break;
            case '06':  $daySelected_last = 30; break;
            case '07':  $daySelected_last = 31; break;
            case '08':  $daySelected_last = 31; break;
            case '09':  $daySelected_last = 30; break;
            case '10':  $daySelected_last = 31; break;
            case '11':  $daySelected_last = 30; break;  
            case '12':  $daySelected_last = 31; break;
            default:    /* code... */  break;
        }
        $day = $daySelected_last;
        $dateTime_Start = "2019-07-01 00:00:00";
        $dateTime_Last = "$yearSelected-$monthSelected-$daySelected_last 23:59:59";
        $dateTime_Select = "$yearSelected-$monthSelected-$daySelected_start 00:00:00";


        $transactions = DB::table('transactions')
        ->select(
            'refNumber as phone',
            'machineId as branch',
            DB::raw('SUM(transactions.price)  AS amount'),
            DB::raw('MIN(transactions.createdAt) AS startDate'),
            DB::raw('MAX(transactions.createdAt) AS lastDate'),
            DB::raw('COUNT(transactions.refNumber)  AS trans'),
            DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS numberOfDays'),
            // DB::raw("DATEDIFF(MAX(transactions.createdAt, '$dateTime_Last')) AS dataDiff"),
            DB::raw("DATEDIFF( '$dateTime_Last',  MAX(transactions.createdAt)) AS lastDayOfUse"),
            DB::raw("YEAR('$dateTime_Last') AS dataOfYear"),
            DB::raw("MONTH('$dateTime_Last') AS dataOfMonth"),
            DB::raw('TIMESTAMPDIFF(month, min(transactions.createdAt), max(transactions.updatedAt)) AS numberOfMonth '),
            DB::raw("CASE 
                WHEN DATEDIFF( MAX(transactions.updatedAt), '$dateTime_Last' ) < -35 AND COUNT(DISTINCT DATE(transactions.updatedAt)) > 1 AND sum(transactions.price) > 1000 then 'Churn_NeedCheck'
                WHEN DATEDIFF( MAX(transactions.updatedAt), '$dateTime_Last' ) < -35 AND COUNT(DISTINCT DATE(transactions.updatedAt)) > 1 THEN 'Churn'
                WHEN DATEDIFF( MAX(transactions.updatedAt), '$dateTime_Last' ) < -35 AND COUNT(DISTINCT DATE(transactions.updatedAt)) <= 1 THEN 'Churn_1stTimeUse'
                ELSE 'Active' 
                END ActiveStatus"),
            DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = $monthSelected AND MIN(YEAR(transactions.createdAt)) = $yearSelected 
                THEN 'New Customer' 
                ELSE 'Old Customer' 
                END customerStatus "),
            DB::raw("CASE WHEN  MONTH('$dateTime_Last') =  MONTH(MAX(transactions.createdAt)) AND YEAR('$dateTime_Last') = YEAR(MAX(transactions.createdAt)) 
                THEN 'TRUE'
                ELSE 'FALSE'
                END useInMonth ")     
        )
        ->where('machineId', $branch)
        ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
        ->groupBy('refNumber', 'machineId')
        ->orderBy('amount', 'DESC')
        ->paginate(500);

        $users = DB::table('transactions')
            ->select(
                'refNumber',
                'machineId',
                DB::raw('MIN(transactions.createdAt) AS startDate'),
                DB::raw('MAX(transactions.createdAt) AS lastDate'),
                DB::raw('COUNT(DATE(createdAt)) AS countTrans'),
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
                DB::raw("DATEDIFF('$dateTime_Last', MAX(transactions.createdAt)) AS dataDiff"),
                DB::raw("CASE WHEN DATEDIFF( MAX(transactions.createdAt) , TIMESTAMPADD(MONTH, -2, '$dateTime_Select') ) > 0
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
                DB::raw('COUNT(DISTINCT DATE(transactions.createdAt)) AS countDate'),
                DB::raw("CASE WHEN MIN(MONTH(transactions.createdAt)) = $monthSelected AND MIN(YEAR(transactions.createdAt)) = $yearSelected 
                    THEN 'new user' 
                    ELSE 'old user' 
                    END statusUser ")
            )
            ->where('machineId', $branch)
            ->whereBetween('createdAt', [$dateTime_Start, $dateTime_Last])
            ->groupBy('refNumber', 'machineId')
            ->having('statusUser', 'new user')
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
                DB::raw("CASE WHEN DATEDIFF( MAX(transactions.createdAt) , TIMESTAMPADD(MONTH, -2, '$dateTime_Select') ) > 0
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
                DB::raw("CASE WHEN DATEDIFF( MAX(transactions.createdAt) , TIMESTAMPADD(MONTH, -2, '$dateTime_Select') ) > 0
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
        $year;
        $month;
        $day;
        $yearEnd = null;

        return view('transactions.index', compact(
            'transactions',
            'countNewUser',
            'countUser',
            'countActiveUser',
            'countDeprecatedUser',
            'branch',
            'year',
            'month',
            'day',
            'yearEnd'
        
        ));
    }
}