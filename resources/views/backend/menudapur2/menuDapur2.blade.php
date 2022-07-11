@extends('crudbooster::layouts.layout')
@section('content')
<style>
    table,tr,td{
        text-align: left !important;
    }
</style>

<div class="row" id="printDiv">
    <div class="col-md-4">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Menu Hari ini</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="100px">Hari / tanggal</td>
                        <td colspan="2">{{ $date }}</td>
                    </tr>
                    @foreach($menu as $key => $row)
                    <tr>
                        <td>{{ ($key == 0 ? 'Menu' : '') }}</td>
                        <td><b>{{ $row->product_id }}</b></td>
                        <td>{{ $row->name }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-sm-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Dapur Hari ini</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">
                @foreach($menu_regular as $m)
                <div class="row">
                    <div class="col-sm-3">
                        <table class="table table-bordered" style="margin-top: 2rem;">
                            <thead>
                             <tr style="background: #5cb85c;color: #fff;">
                                <th>Nama Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $m['product_id'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <table class="table table-bordered" style="margin-top: 2rem;">
                        <thead>
                            <tr style="background: #5bc0de;color: #fff;">
                                <th>Protein</th>
                                <th>Jumlah</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($m['protein'] as $p)
                            <tr>
                                <td>{{ $p['protein'] }}</td>
                                <td>{{ $p['total'] }}</td>
                                <td>
                                    @if(count((array)$p['customer']) != 0)
                                    @foreach($p['customer'] as $key => $c)
                                    - {{ $c['customer'] }}<br>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                                                        
                            @foreach($m['hinprotein'] as $p)
                            <tr>
                                <td>{{ $p['hinprotein'] }}</td>
                                <td>{{ $p['total'] }}</td>
                                <td>
                                    @if(count((array)$p['customer']) != 0)
                                    @foreach($p['customer'] as $key => $c)
                                    - {{ $c['customer'] }}<br>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <table class="table table-bordered" style="margin-top: 2rem;">
                        <thead>
                            <tr style="background: #f0ad4e;color: #fff;">
                                <th>Carbo</th>
                                <th>Jumlah</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($m['carbo'] as $c)
                            <tr>
                                <td>{{ $c['carbo'] }}</td>
                                <td>{{ $c['total'] }}</td>
                                <td>
                                    @if(count((array)$c['customer']) != 0)
                                    @foreach($c['customer'] as $key => $cu)
                                    - {{ $cu['customer'] }}<br>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                            @foreach($m['hincarbo'] as $c)
                            <tr>
                                <td>{{ $c['hincarbo'] }}</td>
                                <td>{{ $c['total'] }}</td>
                                <td>
                                    @if(count((array)$c['customer']) != 0)
                                    @foreach($c['customer'] as $key => $cu)
                                    - {{ $cu['customer'] }}<br>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <table class="table table-bordered" style="margin-top: 2rem;">
                        <thead>
                            <tr style="background: #d9534f;color: #fff;">
                                <th>Alergen</th>
                                <th>Jumlah</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <?php
                                    $total_alrg = 0;
                                    foreach ($m['alergy'] as $key => $t_a) {
                                        $total_alrg += 1;
                                    }
                                    echo $total_alrg;
                                    ?>
                                </td>
                                <td>
                                    @if(count((array)$m['alergy']) == 0)
                                    -
                                    @else
                                    @foreach($m['alergy'] as $key => $a)
                                    - {{ $a['customer'] }}<br>
                                    @endforeach
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <span class="h3 text-center">Total Box: {{ $m['total_box'] }}</span>
            <hr>
            @endforeach
            @foreach($menu_propack as $m)
            <div class="row">
                <div class="col-sm-3">
                    <table class="table table-bordered" style="margin-top: 2rem;">
                        <thead>
                            <tr style="background: #5cb85c;color: #fff;">
                                <th>Nama Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$m['product_id']}} (PP)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <table class="table table-bordered" style="margin-top: 2rem;">
                        <thead>
                            <tr style="background: #5bc0de;color: #fff;">
                                <th>Protein</th>
                                <th>Jumlah</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($m['protein'] as $p)
                            <tr>
                                <td>{{ $p['protein'] }}</td>
                                <td>{{ $p['total'] }}</td>
                                <td>
                                    @if(count((array)$p['customer']) != 0)
                                    @foreach($p['customer'] as $key => $c)
                                    - {{ $c['customer'] }}<br>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                            @foreach($m['hinprotein'] as $p)
                            <tr>
                                <td>{{ $p['hinprotein'] }}</td>
                                <td>{{ $p['total'] }}</td>
                                <td>
                                    @if(count((array)$p['customer']) != 0)
                                    @foreach($p['customer'] as $key => $c)
                                    - {{ $c['customer'] }}<br>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <table class="table table-bordered" style="margin-top: 2rem;">
                        <thead>
                            <tr style="background: #f0ad4e;color: #fff;">
                                <th>Carbo</th>
                                <th>Jumlah</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($m['carbo'] as $c)
                            <tr>
                                <td>{{ $c['carbo'] }}</td>
                                <td>{{ $c['total'] }}</td>
                                <td>
                                    @if(count((array)$c['customer']) != 0)
                                    @foreach($c['customer'] as $key => $cu)
                                    - {{ $cu['customer'] }}<br>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                            @foreach($m['hincarbo'] as $c)
                            <tr>
                                <td>{{ $c['hincarbo'] }}</td>
                                <td>{{ $c['total'] }}</td>
                                <td>
                                    @if(count((array)$c['customer']) != 0)
                                    @foreach($c['customer'] as $key => $cu)
                                    - {{ $cu['customer'] }}<br>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <table class="table table-bordered" style="margin-top: 2rem;">
                        <thead>
                            <tr style="background: #d9534f;color: #fff;">
                                <th>Alergen</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                             <td>
                                <?php
                                $total_alrg = 0;
                                foreach ($m['alergy'] as $key => $t_a) {
                                    $total_alrg += 1;
                                }
                                echo $total_alrg;
                                ?>
                            </td>
                            <td>
                                @if(count((array)$m['alergy']) == 0)
                                -
                                @else
                                @foreach($m['alergy'] as $key => $a)
                                - {{ $a['customer'] }}<br>
                                @endforeach
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
        <span class="h3 text-center">Total Box: {{ $m['total_box'] }}</span>
        <hr>
        @endforeach
        <div class="row">
            <div class="col-sm-4">
                <table class="table table-bordered">
                    <thead>
                        <tr style="background: #9b59b6;color: #fff;">
                            <th>Nama Menu</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menu_secondary as $m)
                        <tr>
                            <td>{{ $m['menu_name'] }}</td>
                            <td>{{ $m['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@push('bottom')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script type="text/javascript">
    $('.btn-export').click(function(){
        screenshot();
    })
    function pix2mm(val,dpi){
        return (val/0.0393701)/dpi;
    }
    function screenshot(){
        width = $("#printDiv").width() + 90;
        height = $("#printDiv").height() + 300;
        width = pix2mm(width,96);
        height = pix2mm(height,96);
        html2canvas(document.getElementById("printDiv"), {
            onrendered: function(canvas) {

                var imgData = canvas.toDataURL('image/png');
                var doc = new jsPDF('p', 'mm', [width, height]); //210mm wide and 297mm high

                doc.addImage(imgData, 'PNG', 10, 10);
                doc.save('EZFIT - Export Menu Dapur {{ date("d F Y")}}.pdf');
            }
        });
    }
</script>
@endpush