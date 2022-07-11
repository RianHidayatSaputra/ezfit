<?php
/** @var $customer \App\Models\Customers */
?>
@extends('crudbooster::layouts.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .labeling{
        padding-right: 20px;
        font-weight: normal;
    }
    .radios{
        padding-left: 10px;
    }
    input{
        font-size: 12px !important;
    }
    .map{
        width: 100%;
        height: 358px;
        border: 1px solid#eaeaea;
    }
</style>
<div class="box box-default">
    <div class="box-body">
        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{action('AdminCustomersController@postEditSave')}}/{{$customer->getId()}}">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-sm-2">Nama Lengkap:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="{{$customer->getName()}}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" value="{{$customer->getEmail()}}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Password:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">No WA:</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="no_wa" value="{{$customer->getHoHp()}}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Tanggal Lahir:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control flatpick" name="tgl_lahir" value="{{$customer->getTglLahir()}}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label col-sm-6">Berat Badan:</label>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input type="number" class="form-control" name="berat_badan" value="{{$customer->getBerat()}}" >
                            <span class="input-group-addon">Kg</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label col-sm-6">Tinggi Badan:</label>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input type="number" class="form-control" name="tinggi_badan" value="{{$customer->getTinggi()}}" >
                            <span class="input-group-addon">Cm</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Jenis Kelamin :</label>
                <div class="col-sm-10">
                    <label class="labeling"> <input type="radio" class="radios" required value="laki - laki" name="jenis_kelamin" <?php if($customer->getGender() == 'laki - laki'){ echo "checked"; } ?>> Laki - laki</label>
                    <label class="labeling"> <input type="radio" class="radios" required value="perempuan" name="jenis_kelamin" <?php if($customer->getGender() == 'perempuan'){ echo "checked"; } ?>> Perempuan</label>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Buku Alamat :</label>
                <div class="col-sm-7">
                    <div style="border:1px solid#eaeaea;width: 100%;min-height: 100px;padding: 30px;">
                        <div class="form-group"  style="display: none">
                            <label class="control-label col-sm-4">Nama Alamat:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nama_alamat" value="-" id="nama_alamat">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Alamat:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="" id="alamat">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Detail Alamat:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="" id="detail_address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4"></label>
                            <div class="col-sm-8">
                                <input type="text" name="latitude" id="lat" class="hidden">
                                <input type="text" name="longitude" id="lng" class="hidden">
                                <div class="map" id="map">

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Nama Penerima Alfternatif:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="" id="nama_penerima">
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label class="control-label col-sm-4">Nomor telpon Alternatif:</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" value="" id="nomor_telpon">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Kurir:</label>
                            <div class="col-sm-8">
                                <select id="kurir" class="form-control select2">
                                    <option value="">Select Kurir</option>
                                    @foreach($driver as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4"></label>
                            <div class="col-sm-8">
                                <a class="btn btn-success" id="tambah_alamat" href="javascript:;">Tambah Alamat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Alamat</th>
                            <th width="20%">Alamat</th>
                            <th>Detail Address</th>
                            <th>Nama Penerima Altf.</th>
                            <th>Kurir</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @foreach($address as $row)
                        <tr id="{{$row->id}}">
                            <td>{{$row->name}} <input type="text" class="hidden" name="nama_alamat[]" value="{{ $row->name }}"></td>
                            <td>{{$row->address}} <input type="text" class="hidden" name="alamat[]" value="{{ $row->address }}"></td>
                            <td>{{$row->detail_address}} <input type="text" class="hidden" name="detail_address[]" value="{{ $row->detail_address }}"> <input type="text" class="hidden" name="lat[]" value="{{ $row->latitude }}"> <input type="text" class="hidden" name="lng[]" value="{{ $row->longitude }}"></td>
                            <td>{{$row->catatan}} <input type="text" class="hidden" name="nama_penerima[]" value="{{ $row->catatan }}"></td>
                            <td>{{$row->kurir}} 
                                <input type="text" class="hidden" name="kurir[]" value="{{ $row->drivers_id }}">
                            </td>
                            <td>
                                <a href="javascript:;" data-id="{{ $row->id }}" class="delete btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label col-sm-4">Foto Krs:</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" placeholder="photo" name="photo_krs" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Masa Berlaku mulai:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control flatpick" name="start_date"  value="{{$customer->getStartDate()}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label col-sm-4">Foto Ktm:</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" placeholder="photo" name="photo_ktm" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Masa Berlaku sampai:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control flatpick" name="end_date" value="{{$customer->getEndDate()}}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-10">
                    <a class="btn btn-default" >Cancel</a>
                    <button class="btn btn-success" name="submit" value="save">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('bottom')

<script>
    $(".flatpick").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDKuY76FBWSQpCoO7a8GJ_h9NpdyLIEID0"></script>
<script>
    $('#tambah_alamat').on('click',function () {
        number = 1 + Math.floor(Math.random() * 9999);
        nama_alamat = $('#nama_alamat').val();
        alamat = $('#alamat').val();
        nama_penerima = $('#nama_penerima').val();
        nomor_telpon = $('#nomor_telpon').val();
        detail_address = $('#detail_address').val();
        lt = $('#lat').val();
        lg = $('#lng').val();
        kurir = $('#kurir').select2().val();
        text_cour = $('#kurir').select2('data')[0].text
        html = '<tr id="'+nomor_telpon+number+'">\n' +
        '<th>'+nama_alamat+'<input type="text" class="hidden" name="nama_alamat[]" value="'+nama_alamat+'"></th>\n' +
        '<th>'+alamat+'<input type="text" class="hidden" name="alamat[]" value="'+alamat+'">' +
        '<th>'+detail_address+'<input type="text" class="hidden" name="detail_address[]" value="'+detail_address+'">' +
        '<input type="text" class="hidden" name="lat[]" value="'+lt+'">' +
        '<input type="text" class="hidden" name="lng[]" value="'+lg+'"></th>\n' +
        '<th>'+nama_penerima+'<input type="text" class="hidden" name="nama_penerima[]" value="'+nama_penerima+'"></th>\n' +
        '<th>'+text_cour+'<input type="text" class="hidden" name="kurir[]" value="'+kurir+'"></th>\n' +
        '<th><a href="javascript:;" data-id="'+nomor_telpon+number+'" class="delete btn btn-danger">Delete</a></th>\n' +
        '</tr>';
        $(html).prependTo( "#tbody" );
    })


    $(document).on('click', '.delete', function () {
        id = $(this).attr("data-id");
        $('#'+id).remove();
    });

    function initialize() {
        var latlng = new google.maps.LatLng(-6.189623, 106.835454);
        map = new google.maps.Map(document.getElementById('map'), {
            center: latlng,
            zoom: 17
        });
        marker = new google.maps.Marker({
            map: map,
            position: latlng,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29)
        });
        var input = document.getElementById('alamat');
                // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                var geocoder = new google.maps.Geocoder();
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);
                var infowindow = new google.maps.InfoWindow();
                autocomplete.addListener('place_changed', function() {
                    infowindow.close();
                    marker.setVisible(false);
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        window.alert("Autocomplete's returned place contains no geometry");
                        return;
                    }

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(20);
                    }

                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                    bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry.location.lng());
                    infowindow.setContent(place.formatted_address);
                    infowindow.open(map, marker);

                });
                // this function will work on marker move event into map
                google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({
                        'latLng': marker.getPosition()
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                bindDataToForm(results[0].formatted_address, marker.getPosition().lat(), marker.getPosition().lng());
                                infowindow.setContent(results[0].formatted_address);
                                infowindow.open(map, marker);
                            }
                        }
                    });
                });
            }
            function bindDataToForm(address, lat, lng) {
                document.getElementById('alamat').value = address;
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;

            }

            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        @endpush
        @endsection