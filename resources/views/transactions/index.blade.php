
@extends('layouts.index')
@section('content')
    <div class="py-12">
        <div class="container my-4">
            <div class="row">
                <div class="col-md-12">
                    @if(session("success"))
                        <div class="alert alert-success">{{ session("success") }}</div>
                    @endif
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
                                        <th scope="col"><center>status</center></th>
                                        <th scope="col"><center>cancel</center></th>
                                        
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
                                        <td><center>{{$row->countTrans}}</center></td>                    
                                        <td><center>{{$row->countDate}}</center></td>
                                        <td><center>{{$row->dataDiff}}</center></td>
                                        <td><center>{{$row->status}}</center></td>
                                        <td><center>{{$row->cancel}}</center></td>
                                    </tr>
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