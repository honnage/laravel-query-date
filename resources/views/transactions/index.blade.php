@extends('layouts.index')
@section('content')
<div class="py-12">
    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                @if(session("success"))
                    <div class="alert alert-success">{{ session("success") }}</div>
                @endif
                
                <div class="row ">
                    <div class="col-md-3">
                        <div class="background-card" style="background-color: rgb(51, 163, 248);">
                            <div class="card-body">
                                <nav class="title">ผู้ใช้งานรายใหม่ </nav>
                                <nav>จำนวน <span style="float: right;"> {{number_format($countNewUser)}} คน </span></nav>
                            </div>   
                        </div>
                    </div>
                    <div class="col-md-3 background-card">
                        <div class="background-card" style="background-color: rgb(20, 36, 162);">
                            <div class="card-body" >
                                <nav class="title">ผู้ใช้งานในระบบทั้งหมด </nav>
                                <nav>จำนวน <span style="float: right;"> {{number_format($countUser)}} คน </span></nav>
                            </div>  
                        </div>
                    </div>
                    <div class="col-md-3 background-card">
                        <div class="background-card" style="background-color: rgb(12, 196, 43);">
                            <div class="card-body" >
                                <nav class="title">ผู้ใช้งานที่ยังใช้บริการ </nav>
                                <nav>จำนวน <span style="float: right;"> {{number_format($countActiveUser)}} คน </span></nav>
                            </div>  
                        </div>
                    </div>
                    <div class="col-md-3 background-card">
                        <div class="background-card" style="background-color: rgb(151, 151, 151);">
                            <div class="card-body" >
                                <nav class="title">ผู้ใช้งานที่เลิกใช้บริการ </nav>
                                <nav>จำนวน <span style="float: right;"> {{number_format($countDeprecatedUser)}} คน </span></nav>
                            </div>  
                        </div>
                    </div>
                </div>

                <form action="{{url('/transactions/store/branch:'.$branch.'/year:'.$year.'/month:'.$month.'/day:'.$day)}}" method="post">
                    @csrf
                    <input type="submit" value="บันทึกลง database" class="btn btn-success my-3">
                </form>
               {{$year}} {{$month}} {{$day}}
                        
                <div class="card">
                    <div class="card-header" style="font-size: 24px;">ตารางข้อมูล Transactions</div>
                    <div class="table-responsive">
                        @if($transactions->count()>0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ลำดับ</th>
                                    <th scope="col">เบอร์โทรศัพท์</th>
                                    <th scope="col"><center>สาขา</center></th>
                                    <th scope="col"><center>startDate</center></th>
                                    <th scope="col"><center>lastDate</center></th>
                                    <th scope="col"><center>จำนวนธุรกรรม</center></th>
                                    <th scope="col"><center>จำนวนวัน</center></th>
                                    <th scope="col"><center>dataDiff</center></th>
                                    <th scope="col"><center>statusUser</center></th>
                                    <th scope="col"><center>usageMonth</center></th>
                                    <th scope="col"><center>statusActive</center></th>
                          
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $transactions as $row )
                                <tr>
                                    <td style="padding-left: 20px;">{{$transactions->firstItem()+$loop->index}}</td>
                                    <td>{{$row->refNumber}}</td>
                                    <td><center>{{$row->machineId}}</center></td>  
                                    <td><center>{{$row->startDate}}</center></td> 
                                    <td><center>{{$row->lastDate}}</center></td> 
                                    <td><center>{{number_format($row->countTrans)}}</center></td>                    
                                    <td><center>{{number_format($row->countDate)}}</center></td>
                                    <td><center>{{number_format($row->dataDiff)}}</center></td>
                                    <td><center>{{$row->statusUser}}</center></td>
                                    <td><center>{{$row->usageMonth}}</center></td>
                                    <td><center>{{$row->statusActive}}</center></td>
                                </tr>
                                <?php $sum = $transactions->firstItem()+$loop->index ?>
                                @endforeach
                                
                            </tbody>
                            
                        </table>
            
                        @else
                            <h3 style="color:red; text-align:center ;padding-top: 20px; padding-bottom: 20px">-- ไม่มีข้อมูล หมวดหมู่ --</h3>
                        @endif
                    </div>
                
                    <div class="pagination-block" style="float:right">
                        {{ $transactions->links('layouts.paginationlinks') }}
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection