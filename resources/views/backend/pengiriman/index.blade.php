@extends('crudbooster::layouts.layout')
@section('content')
<style>
    .box-solid{
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.16);
    }
    #map{
        height: 270px;
        margin-bottom: 30px;
    }
    .select2-container {
        width: 100% !important;
        padding: 0;
    }
    .form-control-static{
        text-align: right;
    }
    #selesai{
        display: none;
    }
    .box-solid{
        background: #fff;
    }
</style>
<div class="overlay loading">
    <div class="overlay__inner">
        <div class="overlay__content"><span class="spinner"></span></div>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/loading.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
<div class="row">
    <div class="col-sm-2">
        <div class="box-solid" style="margin-bottom: 20px;">
            <div class="box-body">
                <table class="table table-responsive">
                    <tr>
                        <td><a href="javascript:;" class="btn btn-xs btn-warning"><i class="fa fa-user-secret"></i></a> </td>
                        <td>Update Kurir</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:;" class="btn btn-xs btn-primary"><i class="fa fa-car"></i></a></td>
                        <td>Update Status</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-10">
        <div class="box box-default">
            <div class="box-body table-responsive no-padding">
                <table class='table table-bordered'>
                    <tbody>
                        <tr class='active'>
                            <td colspan="2">
                                <h5>
                                    <strong><i class='fa fa-bars'></i> Action</strong>
                                </h5>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">
                                <strong>
                                    Filter Status
                                </strong>
                            </td>
                            <td>
                                <select id="filter_sp" class="form-control" required="" name="status_pengiriman">
                                    <option disabled="" selected="">== PILIH STATUS ==</option>
                                    <option>Proses</option>
                                    <option>Dikirim</option>
                                    <option>Selesai</option>
                                </select>
                            </td>
                        </tr>
                        @if(cb()->session()->roleId() != 3)
                        <tr>
                            <td width="25%">
                                <strong>
                                    Filter Kurir
                                </strong>
                            </td>
                            <td>
                                <select id="filter_kurir" class="form-control select2" required="" name="kurir_pd">
                                    <option disabled="" selected="">== PILIH KURIR ==</option>
                                    <option value="All">All</option>
                                    @foreach($driver as $d)
                                    <option value="{{ $d->name }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td width="25%">
                                <strong>
                                    Bulk Change Status
                                </strong>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-5">
                                        <select id="bulk-act" class="form-control" name="bulk-act">
                                            <option selected="">Dikirim</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-success btn-bulk"><i class="fa fa-telegram"></i> Submit</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-body">
        <div class="table-responsive">
            <table id="example" class="table table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        @if(cb()->session()->roleId() != 3)
                        <th></th>
                        <th>No Order</th>
                        <th>Tanggal Pengiriman</th>
                        <th width="150px">Package</th>
                        <th>Alergy</th>
                        <th>Nama Penerima</th>
                        <th width="150px">Alamat</th>
                        <th>Kurir</th>
                        <th>Status Pengiriman</th>
                        <th>No WA</th>
                        <th>Action</th>
                        @else
                        <th></th>
                        <th>Nama Penerima</th>
                        <th>No WA</th>
                        <th width="200px">Alamat</th>
                        <th>Package</th>
                        <th>Status Pengiriman</th>
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                @if(cb()->session()->roleId() != 3)
                <tbody>
                    @foreach($list as $row)
                    <?php
                    if ($row['status_pengiriman'] == 'Proses'){
                        $btn = 'primary';
                    }elseif ($row['status_pengiriman'] == 'Dikirim'){
                        $btn = 'warning';
                    }else{
                        $btn = 'success';
                    }
                    ?>
                    <tr>
                        <td><label class="labeling"><input type="checkbox" class="radios cb-id" value="{{ $row['id'] }}" name="row_id"></label></td>
                        <td>{!! $row['no_order'] !!}</td>
                        <td>{{$row['date']}}</td>
                        <td>{!! $row['package_name'] !!}</td>
                        <td>{!! $row['alargy'] !!}</td>
                        <td>{!! $row['nama_penerima'] !!}</td>
                        <td>{!! $row['address'] !!}</td>
                        <td>{!! $row['driver_name'] !!}</td>
                        <td>
                            <label for="" class="label label-{!! $btn !!} label-xs">{!! $row['status_pengiriman'] !!}</label>
                        </td>
                        <td>{!! $row['no_penerima'] !!}</td>
                        <td>
                            <a href="javascript:;" class="btn btn-xs btn-warning set-driver"  data-type="{{$row['type_alamat']}}"  data-order="{{$row['id']}}"><i class="fa fa-user-secret"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-primary modal-status" data-order="{{$row['id']}}" ><i class="fa fa-car"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                <tbody>
                    @foreach($list as $row)
                    <?php
                    if ($row['status_pengiriman'] == 'Proses'){
                        $btn = 'primary';
                    }elseif ($row['status_pengiriman'] == 'Dikirim'){
                        $btn = 'warning';
                    }else{
                        $btn = 'success';
                    }
                    ?>
                    <tr>
                        <td><label class="labeling"><input type="checkbox" class="radios cb-id" value="{{ $row['id'] }}" name="row_id"></label></td>
                        <td>{!! $row['nama_penerima'] !!}</td>
                        <td>{!! $row['no_penerima'] !!}</td>
                        <td>{!! $row['address'] !!}</td>
                        <td>{!! $row['package_name'] !!}</td>
                        <td>
                            <label for="" class="label label-{!! $btn !!} label-xs">{!! $row['status_pengiriman'] !!}</label>
                        </td>
                        <td>
                            <a href="javascript:;" class="btn btn-xs btn-warning set-driver" data-type="{{$row['type_alamat']}}"  data-order="{{$row['id']}}"><i class="fa fa-user-secret"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-primary modal-status" data-order="{{$row['id']}}" ><i class="fa fa-car"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-update">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update Status</h4>
                </div>
                <form action="{{action('AdminPengirimanController@postUpdateStatus')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="trx_orders_id" id="trx_orders_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Status Pengiriman</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="status_pengiriman" id="select-status">
                                    <option value="">Pilih Status</option>
                                    {{--<option value="Proses">Proses</option>--}}
                                    <option value="Dikirim">Dikirim</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div id="selesai">
                            <div class="form-group">
                                <img src="" alt="" id="img-bukti" class="img-responsive">
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bukti Pengiriman</label>
                                <div class="col-sm-8">
                                    <input type="file" name="photo_pengiriman" accept="image/*" class="form-control selesai">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Catatan Kurir</label>
                                <div class="col-sm-8">
                                    <textarea name="catatan_pengiriman"  cols="30" rows="10" class="form-control selesai"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--modal edit driver--}}
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Order Detail</h4>
                    </div>
                    <form action="{{action('AdminPengirimanController@postUpdateDriver')}}" method="post" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="map"></div>
                                </div>
                                <div class="col-sm-6">
                                    <input type="hidden" name="id" id="id">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tanggal Pengiriman</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static" id="tgl_pengiriman"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nomor Order</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static" id="nomor_order"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Package</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static" id="packages"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nama Penerima</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static" id="nama_penerima"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">No Telp</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static" id="no_telp"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Alamat</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static" id="alamat"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Detail Alamat</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static" id="detail_alamat"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Alternatif Penerima</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static" id="catatan"></p>
                                        </div>
                                    </div>
                                    @if(cb()->session()->roleId() != 3)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Kurir</label>
                                        <div class="col-sm-8">
                                            <select name="drivers_id" id="drivers_id" required class="form-control select2">
                                                <option value="">* Pilih Kurir</option>
                                                @foreach($driver as $row)
                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            @if(cb()->session()->roleId() != 3)
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            @endif
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        @push('bottom')
        <script src="https://maps.google.com/maps/api/js?key=AIzaSyDKuY76FBWSQpCoO7a8GJ_h9NpdyLIEID0&libraries=places"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
        <script>
            $('#select-status').on('change', function() {
                if ( this.value == 'Selesai'){
                    // $(".selesai").prop('required',true);
                    $('#selesai').show();
                }else{
                    // $(".selesai").prop('required',false);
                    $('#selesai').hide();
                }
            });

            $('.modal-status').on('click',function () {
                id_order = $(this).attr("data-order");
                $('.loading').show();
                $('#trx_orders_id').val(id_order);
                $.getJSON( "{{action('AdminPengirimanController@getStatus')}}?id="+id_order, function( data ) {
                    console.log(data);
                    if (data.status_pengiriman != 'Proses'){
                        $('#select-status').val(data.status_pengiriman);

                        if (data.status_pengiriman =='Selesai'){
                            $(".selesai").prop('required',true);
                            $('#selesai').show();
                            $('#img-bukti').attr('src',data.photo_pengiriman);
                            $('textarea[name="catatan_pengiriman"]').text(data.catatan_pengiriman);
                        }else{
                            $(".selesai").prop('required',false);
                            $('#selesai').hide();
                        }
                    }else{
                        $('#select-status').val('');
                        $(".selesai").prop('required',false);
                        $('#selesai').hide();
                    }
                }).done(function() {
                    $('#modal-update').modal('show');
                    $('.loading').hide();
                });
            });

            $(document).ready(function() {
                $('.btn-bulk').on('click', function(){
                    var row_id = [];
                    $("input[name='row_id']:checked").each(function(){
                        row_id.push(this.value);
                    });

                    if (row_id.length < 1) {
                        swal({
                            icon: "warning",
                            text: "Pilih Beberapa Orderan Terlebih Dahulu!",
                        });
                    }else{
                        $('.loading').show();
                        $.ajax({
                            url: '{{ action("AdminPengirimanController@postKirim") }}',
                            type: 'POST',
                            data: {id:row_id},
                            dataType: 'JSON',
                            success: function(response){
                                swal({
                                    icon: "success",
                                    text: "Berhasil Mengubah Status!",
                                });
                                location.reload();
                            },
                            error: function(response) {
                              console.log(response);
                          },
                      });
                    }
                });
                var table = $('#example').DataTable({dom: 'lftipr'});

                @if(cb()->session()->roleId() != 3)
                $('#filter_sp').on('change', function(){table.column(8).search(this.value).draw();});
                @else
                $('#filter_sp').on('change', function(){table.column(5).search(this.value).draw();});
                @endif

                $('#filter_kurir').on('change', function(){
                    if (this.value == 'All') {
                        table.column(7).search('').draw();  
                    }else{
                        table.column(7).search(this.value).draw();  
                    } 
                });
            });

            $('.set-driver').on('click',function () {
                $('.loading').show();
                id_order = $(this).attr("data-order");
                type_alamat = $(this).attr("data-type");
                type_alamat = parseInt(type_alamat);
                $.getJSON( "{{action('AdminPengirimanController@getDetailPengiriman')}}?id="+id_order, function( data ) {

                    // map
                    if(type_alamat == 1){
                        console.log(11);
                        var a_lat = data.latitude;
                        var a_lng = data.longitude;
                        var adrs = data.address;
                        var cttn = data.catatan;
                        var adrs_dtl = data.detail_address;
                        var driver = data.drivers_id;
                    }else{
                        var adrs = data.address_second;
                        var adrs_dtl = data.detail_address_second;
                        var a_lat = data.latitude_second;
                        var a_lng = data.longitude_second;
                        var cttn = data.catatan_second;
                        var driver = data.drivers_id_second;
                    }

                    var locations = [
                    [ data.address_book_id+'<br>'+adrs ,a_lat , a_lng, 1],
                    ];

                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 17,
                        center: new google.maps.LatLng( a_lat, a_lng),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    var infowindow = new google.maps.InfoWindow();

                    var marker, i;

                    for (i = 0; i < locations.length; i++) {
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                            map: map
                        });

                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                infowindow.setContent(locations[i][0]);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                    }
                    // detail
                    $('#tgl_pengiriman').html(data.tgl_mulai);
                    $('#id').val(data.id);
                    $('#nomor_order').html(data.no_order);
                    $('#packages').html(data.package_name);
                    $('#nama_penerima').html(data.nama_penerima);
                    $('#no_telp').html(data.no_penerima);
                    $('#alamat').html(adrs);
                    $('#detail_alamat').html(adrs_dtl);
                    $('#catatan').html(cttn);

                    @if(cb()->session()->roleId() != 3)
                        $("#drivers_id").select2("val", driver);
                    @endif

                }).done(function() {
                    $('#modal-default').modal('show');
                    $('.loading').hide();
                })
            });
        </script>
        @endpush
        @endsection