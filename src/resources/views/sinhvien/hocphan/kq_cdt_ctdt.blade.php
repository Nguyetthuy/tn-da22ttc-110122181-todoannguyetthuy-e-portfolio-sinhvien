@extends('sinhvien.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">
                            {{ __('Assessment Planning') }}<noscript></noscript>
                            <nav></nav>
                        </h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ asset('/giang-vien') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ asset('/giang-vien/quy-hoach-danh-gia')}}">Quy hoach danh gia</a></li>
                          
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
 @php
                  function sxmang2chieu(&$mang, $n)
                      {
                          for($i=0; $i<$n-1; $i++)
                             { 
                              
                                for($j= $i+1; $j<$n; $j++)
                                   {
                                        if($mang[$i][3]> $mang[$j][3])
                                        {   
                                           
                                            $tam=$mang[$i][0];
                                            $tam1=$mang[$i][1];
                                            $tam2=$mang[$i][2];
                                            $tam3=$mang[$i][3];
                                            $tam4=$mang[$i][4];

                                            $mang[$i][0]= $mang[$j][0];
                                            $mang[$i][1]= $mang[$j][1];
                                            $mang[$i][2]= $mang[$j][2];
                                            $mang[$i][3]= $mang[$j][3];
                                            $mang[$i][4]= $mang[$j][4];

                                            $mang[$j][0]= $tam;
                                            $mang[$j][1]=$tam1;
                                            $mang[$j][2]=$tam2;
                                            $mang[$j][3]=$tam3;
                                            $mang[$j][4]=$tam4;



                                        }
                                    
                                         }
                                  }
                                 // return $mang;
                                        
                            }
                    @endphp

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ __('Semester') }}: <b>{{ Session::get('maHKNH') }}</b>   <b></b>
                                  
                                </h3>
                                <div class="card-tools">
                                    <a href="{{ asset('/giang-vien/quy-hoach-danh-gia') }}" class="btn btn-success"><i
                                            class="fas fa-arrow-left"></i></a>
                                </div>
                            </div>
<br>
                            <h4>
                                 Mã HP: <b>{{$hocPhan->maHocPhan_VB}};</b>
                                 Tên HP: <b>{{$hocPhan->tenHocPhan}}</b>
                            </h4>
                                
                           
                          <br>
                            @foreach ($Sinh_vien as $sv)
                            <h4> MSSV : <b> {{ $sv->maSSV}}; </b>
                               
                                Họ và Tên :<b> {{ $sv->HoSV}} {{ $sv->TenSV}}</b>
    
                            </h4>
                            
                            @endforeach
                            <br>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @if (isset($message) || empty($mang2chieu) || (count($mang2chieu) == 1 && empty($mang2chieu[0])))
                                    <div class="alert alert-info alert-dismissible" style="border-left: 5px solid #0056b3; background-color: #e9f2fe; color: #004085;">
                                        <h5><i class="icon fas fa-info-circle"></i> Thông báo!</h5>
                                        {{ $message ?? 'Chưa có dữ liệu đánh giá CĐR CTĐT cho học phần này!' }}
                                    </div>
                                @else
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('No.') }}</th>
                                                <th>Mã CĐR CTĐT (PI)</th>
                                                <th>Tên CĐR CTĐT (PI)</th>
                                                <th>Điểm</th>
                                                <th>Trong Số</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($mang2chieu as $kq=>$mang1chieu)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    
                                                    <td>{{$mang1chieu[1]}}
                                                    </td>
                                                    <td>{{$mang1chieu[2]}}
                                                    </td>
                                                    <td>{{$mang1chieu[3]}}
                                                    </td>
                                                    <td>{{$mang1chieu[4]}}
                                                    </td>
                                                   
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot></tfoot>
                                    </table>

                                    @php
                                        function Ktmang($mang, $n, $x)
                                        {
                                            for($i=0;$i<$n;$i++)
                                            {
                                                if($mang[$i]==$x)
                                                    return 1;
                                            }
                                            return 0;
                                        }
                                        $mang = array();   $spt=0;
                                        $mang_vb=array();
                                      
                                        foreach ($mang2chieu as $kq=>$mang1chieu)
                                        {
                                            $x=$mang1chieu[0];
                                            $x1=$mang1chieu[1];
                                            if(Ktmang($mang, $spt, $x)==0)
                                            {
                                                $mang[$spt]=$x;     
                                                $mang_vb[$spt]=$x1;                                    

                                                $spt++;
                                            }
                                            # code...
                                        }
                                        //sap xep
                                            for($k=0;$k<$spt-1;$k++)
                                            {      
                                                for($t=$k+1;$t<$spt; $t++)
                                                {
                                                    if($mang_vb[$k]>$mang_vb[$t])
                                                    {
                                                        $tam= $mang_vb[$k];
                                                        $mang_vb[$k]= $mang_vb[$t];
                                                        $mang_vb[$t]= $tam;
                                                    }
                                                }                                                               
                                            }                                  
                                        $mang2chieutam = array(array());                                 
                                        $sodong=0;


                                        sxmang2chieu($mang2chieu, $spt);


                                        for($k=0; $k<$spt; $k++)
                                        {
                                            $diem=0;
                                            $trongso=0;

                                            foreach ($mang2chieu as $kq=>$mang1chieu)
                                            {
                                               if($mang_vb[$k] == $mang1chieu[1])
                                               {
                                                  
                                                    $mang2chieutam[$sodong][0]= $mang1chieu[0];
                                                    $mang2chieutam[$sodong][1]= $mang1chieu[1];
                                                    $mang2chieutam[$sodong][2]= $mang1chieu[2];

                                                    $diem += $mang1chieu[3]*$mang1chieu[4];
                                                    $trongso+=$mang1chieu[4];
                                               


                                               }
                                            }

                                            $mang2chieutam[$sodong][3]=$diem;
                                            $mang2chieutam[$sodong][4]= $trongso;

                                            $sodong++;
                                        }                                    
                                        @endphp
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">{{ __('No.') }}</th>
                                                    <th rowspan="2">Mã CĐR CTĐT (PI)</th>
                                                    <th rowspan="2">Tên CĐR CTĐT (PI) </th>
                                                    <th colspan="4">Mức đạt</th>
                                                    <th rowspan="2" title="">Không đạt</th>
                                                    <th rowspan="2" title="">Điểm</th>
                                                   
                                                </tr>
                                                <tr>
                                                    <th>A</th>
                                                    <th>B</th>
                                                    <th>C</th>
                                                    <th>D</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($mang2chieutam as $kq=>$mang1chieu)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        
                                                        <td>{{$mang1chieu[1]}}
                                                        </td>
                                                        <td>{{$mang1chieu[2]}}
                                                        </td>
                                                        
                                                        @php
                                                           $mang=['','','','',''];
                                                           $kq=$mang1chieu[3]/$mang1chieu[4];

                                                            if($kq>=90)
                                                                 $mang[0]='X';
                                                            else
                                                            {
                                                                if($kq>=70)
                                                                {
                                                                        $mang[1]='X';
                                                                }
                                                                else
                                                                {
                                                                    if($kq>=55)
                                                                    {
                                                                        $mang[2]='X';
                                                                    }
                                                                    else
                                                                    {
                                                                        if($kq>=40)
                                                                        {
                                                                            $mang[3]='X';
                                                                        }
                                                                        else
                                                                        {
                                                                            $mang[4]='X';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        @for ($j=0;$j<=4;$j++)
                                                        <td>{{$mang[$j]}}</td>

                                                        @endfor                                                 
                                                        <td>{{round($kq*1.0/10,1)*1.00}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
