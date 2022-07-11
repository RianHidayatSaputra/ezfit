@extends('crudbooster::layouts.layout')
@section('content')
<div class="overlay loading">
    <div class="overlay__inner">
        <div class="overlay__content"><span class="spinner"></span></div>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/loading.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php
if ($row->day_off){
    $do = json_decode($row->day_off);
}else{
    $do = [];
}

if ($row->protein){
    $pr = json_decode($row->protein);
}else{
    $pr = [];
}

if ($row->carbo){
    $ca = json_decode($row->carbo);
}else{
    $ca = [];
}
?>
<style>
    p{
        color: #000;
    }
    .labeling{
        padding-right: 20px;
        font-weight: normal;
    }
    .radios{
        padding-left: 10px;
        margin-right: 10px;
    }
    .form-control-static{
        padding-top : 0px;
    }
    input{
        font-size: 12px !important;
    }
    .custom-labeling{
        width: 100%;
        padding: 10px;
        border: 1px solid#eaeaea;
    }
    input.radios {
        margin-right: 10px;
    }
    .map{
        width: 100%;
        height: 300px;
        border: 1px solid#eaeaea;
    }
    span.text-right{
        float: right;
    }
</style>
<p>
    <a href="{{url('admin/trx_orders')}}?status={{g('status')}}"><i class="fa fa-arrow-left"></i> Back To Data List</a>
</p>
<div class="row">
    <div class="col-sm-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-text-width"></i>

                <h3 class="box-title"><b>{!! $row->no_order !!}</b></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group" style="margin-top: 20px;">
                    <label class="control-label col-sm-4">Customer </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">{!! $row->c_name !!}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">No Telp </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">{{$row->ho_hp}}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Periode </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">{{$row->periode}} Hari</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Type Package </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">{{ $row->package_type }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Package </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">{!! $row->p_name !!}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Tanggal Mulai </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">{!! date('D, d M Y',strtotime($row->tgl_mulai)) !!}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Alergi </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">
                            @foreach($alergen as $l)
                            {!! $l->name !!},
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Protein yang dihindari </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">
                            @foreach($pr as $pro)
                            {!! $pro->protein !!},
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Carbs yang dihindari </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">
                            @foreach($ca as $car)
                            {!! $car->carbo !!},
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-book"></i>

                <h3 class="box-title">Detail Payment</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group" style="margin-top: 20px;">
                    <label class="control-label col-sm-4">Tanggal Pembayaran</label>
                    <div class="col-sm-8">
                        @if(!empty($row->date_payment))
                        <p class="form-control-static">{{date('D, d M Y H:i', strtotime($row->date_payment))}}</p>
                        @else
                        <p class="form-control-static"> - </p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Nomor Rekening</label>
                    <div class="col-sm-8">
                        @if($row->no_rek)
                        <p class="form-control-static">{{$row->no_rek}}</p>
                        @else
                        <p class="form-control-static"> - </p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Nama Pemilik Rekening</label>
                    <div class="col-sm-8">
                        @if($row->nama_rek)
                        <p class="form-control-static">{{$row->nama_rek}}</p>
                        @else
                        <p class="form-control-static"> - </p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Photo Lampiran</label>
                    <div class="col-sm-8">
                        @if($row->photo_payment)
                        <img src="{{asset($row->photo_payment)}}" alt="" class="img-responsive">
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if($row->status_payment == 'Confirmation')
        <form action="{{ action('AdminTrxOrdersController@postConfirmation') }}" method="POST">
            {{ csrf_field() }}
            <input type="text" name="trx_id" value="{{ $row->id }}" hidden="">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="fa fa-money"></i>
                    <h3 class="box-title">Konfirmasi Pembayaran</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Select Driver Alamat 1:</label>
                        <div class="col-sm-8">
                            <select id="driver_id" name="driver_id" class="form-control select2">
                                <option selected="" disabled="">Select Driver</option>
                                @foreach($all_driver as $driver_list)
                                <option{{ ($row->drivers_id == $driver_list->id ? ' selected' : '')}} value="{{ $driver_list->id }}">{{$driver_list->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    @if($row->address_second)
                    <div class="form-group">
                        <label class="control-label col-sm-4">Select Driver Alamat 2:</label>
                        <div class="col-sm-8">
                            <select id="driver_id" name="driver_id_second" class="form-control select2">
                                <option selected="" disabled="">Select Driver</option>
                                @foreach($all_driver as $driver_list)
                                <option{{ ($row->drivers_id_second == $driver_list->id ? ' selected' : '')}} value="{{ $driver_list->id }}">{{$driver_list->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div style="float: right; margin-top: 1rem; margin-right: ">
                            <button type="submit" class="btn btn-success"><i class="fa fa-telegram"></i> Konfirmasi</button>
                            <a class="btn btn-danger" href="{{ action('AdminTrxOrdersController@getUpdateStatus').'?id='.$row->id.'&status=Failed' }}"><i class="fa fa-trash"></i> Reject</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
    <div class="col-md-6">
        <!-- Custom Tabs (Pulled to the right) -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="true">Alamat 1</a></li>
                @if(!empty($row->latitude_second))
                <li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">Alamat 2</a></li>
                @endif
                <li class="pull-left header"><i class="fa fa-map-marker"></i> Alamat Pengiriman</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                    <div class="form-group" style="display: none">
                        <label class="control-label col-sm-4">Nama Alamat </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{!! $row->address_book_id !!}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Alamat </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{!! $row->address !!}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Detail Alamat </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{!! $row->detail_address !!}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Dikirim Hari </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                {{ $row->day_for }}
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Alternatif Penerima </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $row->catatan }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Kurir </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $driver_first }}</p>
                        </div>
                    </div>
                    <div id="map-1" class="map"></div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2-2">
                    <div class="form-group" style="display: none">
                        <label class="control-label col-sm-4">Nama Alamat </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{!! $row->address_name_second !!}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Alamat </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{!! $row->address_second !!}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Detail Alamat </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{!! $row->detail_address_second !!}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Dikirim Hari </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $row->day_for_altf }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Alternatif Penerima </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $row->catatan_altf }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Kurir </label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $driver_second }}</p>
                        </div>
                    </div>
                    <div id="map-2" class="map"></div>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Realisasi Pengiriman</a></li>
                <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Tunda Langganan</a></li>
                <li><a href="#tab_3" data-toggle="tab" aria-expanded="true">Tanggal Pengiriman</a></li>
                <li class="pull-right header"><i class="fa fa-calendar"></i> History</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <p>
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal-pengiriman"><i class="fa fa-plus"></i> Tambah Tanggal Pengiriman</button>
                    </p>
                    <table class="table table-bordered">
                        <tr>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Detail</th>
                            <th class="text-right">Action</th>
                        </tr>
                        <?php $i =1; ?>
                        @foreach($history as $h)<?php
                        if ($h->status == 'Proses'){
                            $btn = 'primary';
                        }elseif ($h->status == 'Dikirim'){
                            $btn = 'warning';
                        }else{
                            $btn = 'success';
                        }
                        ?>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{ date('D, d M Y', strtotime($h->date)) }}</td>
                            <td>
                                <a href="javascript:;" data-id="{{ $h->trx_orders_id }}" data-tgl="{{$h->date}}" class="btn btn-xs btn-{{ $btn }} {{ ($h->status == 'Selesai' ? ' check-detail' : '')}}">{{ $h->status }}</a>
                            </td>
                            <td class="text-right">
                                <a href="javasript:;" class="btn btn-xs btn-danger" onclick="deleteConfirmation('{{action("AdminTrxOrdersController@getDeletePengiriman")}}?id={{$row->id}}&date={{$h->date}}')" ><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Detail</th>
                        </tr>
                        <?php $i =1; ?>
                        @foreach($data_paused as $p)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{ $p->date }}</td>
                            <td><span class="btn btn-xs btn-danger">Tertunda</span></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Detail</th>
                        </tr>
                        </tr>
                        <?php $i =1; ?>
                        @foreach($pengiriman as $h)
                            <?php
                                $check = \App\Models\TrxOrdersStatus::simpleQuery()
                                ->where('trx_orders_id',$row->id)
                                ->where('date',$h->date)
                                ->count();
                                if($check > 0){
                                    $status = '<label class="label label-success">Sended</label>';
                                }else{
                                    $status = '<label class="label label-danger">Not Sended</label>';
                                }
                            ?>
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{ date('D, d M Y', strtotime($h->date)) }}</td>
                                <td>{!! $status !!}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
</div>

<div id="detailPengiriman" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detail Pengiriman</h4>
    </div>
    <div class="modal-body">
        <table class="table">
            <tbody>
                <tr>
                    <td>Status Pengiriman</td>
                    <td>:</td>
                    <td><span class="btn btn-success btn-xs">Selesai</span></td>
                </tr>
                <tr>
                    <td>Bukti Pengiriman</td>
                    <td>:</td>
                    <td>
                        <a id="preview-image-a" data-lightbox="preview-image" title="Preview Image" href="">
                            <img id="preview-image-img" src="" style="height: 320px" alt="Photo">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Catatan Kurir</td>
                    <td>:</td>
                    <td id="catatan_kurir"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>

</div>
</div>

<!-- Modal -->
<div id="modal-pengiriman" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{action('AdminTrxOrdersController@postAddDate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$row->id}}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Tanggal pengiriman</h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control flatpick" name="date_start" value="{{old('date')}}" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </form>
        </div>

    </div>
</div>
@push('bottom')

<script>
    $('.check-detail').on('click',function () {
        $('.loading').show();
        id = $(this).attr('data-id');
        date = $(this).attr('data-tgl');
        $.ajax({
            url: "{{ action('AdminPengirimanController@getFinishDetail') }}?id="+id+'&date='+date,
            cache: false,
            success: function(result){
                $('#detailPengiriman').modal('show');
                $('#preview-image-a').attr('href',result.img);
                $('#preview-image-img').attr('src',result.img);
                $('#catatan_kurir').html(result.driver_note);
            }
        }).done(function() {
            $('.loading').hide();
        });
    })
</script>

<script>
    $(".flatpick").flatpickr({
        "disable": [
            function(date) {
                // return true to disable
                return (date.getDay() === 0);

            }
        ],
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });
    function initializeGoogleMap()
    {
        var latlng = new google.maps.LatLng(<?php echo $row->latitude; ?>,<?php echo $row->longitude; ?>);
        @if(!empty($row->latitude_second))
        var latlng2 = new google.maps.LatLng(<?php echo $row->latitude_second; ?>,<?php echo $row->longitude_second; ?>);
        @endif

        var myOptions =
        {
            zoom: 13,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        @if(!empty($row->latitude_second))
        var myOptions2 =
        {
            zoom: 13,
            center: latlng2,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        @endif


        var map = new google.maps.Map(document.getElementById("map-1"), myOptions);
        @if(!empty($row->latitude_second))
        var map2 = new google.maps.Map(document.getElementById("map-2"), myOptions2);
        @endif

        var myMarker = new google.maps.Marker(
        {
            position: latlng,
            map: map,
            title:"{{$row->address_book_id}}"
        });
        @if(!empty($row->latitude_second))
        var myMarker2 = new google.maps.Marker(
        {
            position: latlng2,
            map: map2,
            title:"{{$row->address_name_second}}"
        });
        @endif

    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKuY76FBWSQpCoO7a8GJ_h9NpdyLIEID0&callback=initializeGoogleMap">
</script>
@endpush
@endsection