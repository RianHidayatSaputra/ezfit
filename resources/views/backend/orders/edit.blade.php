<?php
/** @var $order \App\Models\TrxOrders */
$holiday = DB::table('holidays')->get();
$day_off = json_decode($order->getDayOff());
$do = [];
if(!empty($day_off)){
    foreach ($day_off as $row){
        $do[] = strtolower($row->day_off);
    }
}

$pro_tein = json_decode($order->getProtein());
$pr = [];
if(!empty($pro_tein)){
    foreach ($pro_tein as $row){
        $pr[] = $row->protein;
    }
}

$car_bo = json_decode($order->getCarbo());
$ca = [];
if(!empty($car_bo)){
    foreach ($car_bo as $row){
        $ca[] = $row->carbo;
    }
}

$al = [];
if (!empty($alergi)){
    foreach ($alergi as $row){
        $al[] = $row->master_alergy_id;
    }
}

$day_for = explode(',',$order->getDayFor());
$df = [];
if (!empty($day_for)) {
    foreach ($day_for as $row) {
        $df[] = $row;
    }
}

$day_for_altf = explode(',',$order->getDayForAltf());
$dfa = [];
if (!empty($day_for_altf)) {
    foreach ($day_for_altf as $row) {
        $dfa[] = $row;
    }
}
$date_select = '';
foreach ($selected as $l){
    $date_select .= $l->date.' :: ';
}
$date_select = substr($date_select, 0, -4);
?>
@extends('crudbooster::layouts.layout')
@section('content')
    <div class="overlay loading">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>
    <link rel="stylesheet" href="{{asset('css/loading.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .labeling{
            padding-right: 20px;
            font-weight: normal;
        }
        .radios{
            padding-left: 10px;
            margin-right: 10px;
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
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 15px;
            right: 15px;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
        .autocomplete {
            position: relative;
            display: inline-block;
        }
    </style>
    <div class="box box-default">
        <div class="box-body">
            <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{action('AdminTrxOrdersController@postEditSave')}}/{{$order->getId()}}">
                {{ csrf_field() }}
                <input type="text" name="status_url" value="{{ g('status') }}" hidden="">
                <div class="form-group">
                    <label class="control-label col-sm-2">Customer:</label>
                    <div class="col-sm-4">
                        <select id="customer_id" name="customer_id" class="form-control select2">
                            <option value="">Select Customer</option>
                            @foreach($customer as $row)
                                <option value="{{$row->id}}" <?php if($row->id == $order->getCustomersId()->getId()){ echo 'selected'; } ?>>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Pilih Type Package :</label>
                    <div class="col-sm-10">
                        <label class="labeling"> <input type="radio" class="radios type_package" required value="Regular" name="type_package"<?php if($order->getPackagesId()->getTypePackage() == "Regular"){ echo ' checked'; } ?>> Regular</label>
                        <label class="labeling"> <input type="radio" class="radios type_package"<?php if($order->getPackagesId()->getTypePackage() == "Propack"){ echo ' checked'; } ?> required value="Propack" name="type_package"> Propack</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Pilih Periode :</label>
                    <div class="col-sm-10">
                        <label class="labeling"> <input type="radio" <?php if(1 == $order->getPeriode()){ echo 'checked'; } ?> class="radios periode" required value="1" name="periode"> 1 Hari</label>
                        <label class="labeling"> <input type="radio" <?php if(6 == $order->getPeriode()){ echo 'checked'; } ?> class="radios periode" required value="6" name="periode"> 6 Hari (save 1)</label>
                        <label class="labeling"> <input type="radio" <?php if(24 == $order->getPeriode()){ echo 'checked'; } ?> class="radios periode" required value="24" name="periode"> 24 hari (save 30%-50%)</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Pilih Package :</label>
                    <div class="col-sm-10">
                        <div class="row" id="package_list">
                            @foreach($list as $row)
                                <div class="col-sm-6">
                                    <label class="labeling custom-labeling">
                                        <input type="radio" class="radios" required value="{{$row['id']}}" <?php if($row['id'] == $order->getPackagesId()->getId()){ echo 'checked'; } ?> name="packet"> {!! $row['product'] !!}
                                        <span class="text-right">Rp. {{$row['price']}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="text" name="price" id="price-field" value="{{$order->getPrice()}}" style="display: none;">
                <div class="form-group" id="voucher_button_form">
                    <label class="control-label col-sm-2">Voucher:</label>
                    <div class="col-sm-3">
                        <button class="btn btn-success" type="button" id="voucher_button" data-toggle="collapse" data-target="#voucher_form" aria-expanded="false" aria-controls="voucher_form"><i class="fa fa-plus"></i> Tambah Voucher</button>
                    </div>
                </div>
                <div id="voucher_form"{{ ($order->getVouchersCode() == NULL ? ' hidden' : '') }}>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Kode Voucher:</label>
                        <div class="col-sm-3">
                            <input type="text" name="vouchers_code" value="{{ $order->getVouchersCode() }}" class="form-control"/>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-success" id="vouchers_add" type="button"><i class="fa fa-telegram"></i> Submit</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Sub Total:</label>
                        <div class="col-sm-3">
                            <input type="text" name="sub_total" readonly="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Nominal Voucher:</label>
                        <div class="col-sm-3">
                            <input type="text" name="nominal_voucher" readonly="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Total:</label>
                        <div class="col-sm-3">
                            <input type="text" id="total_diskon" name="total_diskon_show" readonly="" class="form-control">
                        </div>
                    </div>
                </div>
                <input type="text" name="total_diskon" hidden="">
                <div class="form-group">
                    <label class="control-label col-sm-2">Metode pembayaran:</label>
                    <div class="col-sm-10">
                        <label class="labeling"> <input type="radio" class="radios" required value="direct transfer" name="metode_payment" <?php if('direct transfer' == $order->getPaymentMethod()){ echo 'checked'; } ?>> Direct Transfer</label>
                        <label class="labeling"> <input type="radio" class="radios" required value="virtual account" name="metode_payment" <?php if('virtual account' == $order->getPaymentMethod()){ echo 'checked'; } ?>> Virtual Account</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Date:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control flatpick" name="date_start" value="{!! $date_select !!}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Alergen:</label>
                    <div class="col-sm-10">
                        @foreach($master_alergy as $row)
                            @if(in_array($row->id, $al))
                                <label class="labeling"> <input type="checkbox" checked value="{{$row->id}}" name="alergy[]"> {{$row->name}} <small>{{$row->detail}}</small></label>
                            @else
                                <label class="labeling"> <input type="checkbox"  value="{{$row->id}}" name="alergy[]"> {{$row->name}} <small>{{$row->detail}}</small></label>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Kandungan protein yang dihindari:</label>
                    <div class="col-sm-10">
                        @foreach($protein as $row)
                            @if(in_array($row->name, $pr))
                                <label class="labeling"> <input type="checkbox" checked class="protein_from" value="{{$row->name}}" name="protein_from[]"> {{$row->name}}</label>
                            @else
                                <label class="labeling"> <input type="checkbox" class="protein_from" value="{{$row->name}}" name="protein_from[]"> {{$row->name}}</label>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="form-group" id="protein_alternative_form"{{ ($order->getProteinAlternative() == NULL ? ' hidden' : '') }}>
                    <label class="control-label col-sm-2">Kandungan protein Alternatif:</label>
                    <div class="col-sm-10">
                        @foreach($protein as $row)
                            <label class="labeling"> <input type="radio" class="radios pro_alt" value="{{$row->name}}" <?php if($row->name == $order->getProteinAlternative()){ echo 'checked'; } ?> name="protein_alternative"> {{$row->name}}</label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Kandungan Carbs yang dihindari:</label>
                    <div class="col-sm-10">
                        @foreach($carbon as $row)
                            @if(in_array($row->name, $ca))
                                <label class="labeling"> <input type="checkbox" checked class="carbo_from" value="{{$row->name}}" name="carbo_from[]"> {{$row->name}}</label>
                            @else
                                <label class="labeling"> <input type="checkbox" class="carbo_from" value="{{$row->name}}" name="carbo_from[]"> {{$row->name}}</label>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="form-group" id="carbo_alternative_form"{{ ($order->getCarboAlternative() == NULL ? ' hidden' : '') }}>
                    <label class="control-label col-sm-2">Kandungan Carbs Alternatif</label>
                    <div class="col-sm-10">
                        @foreach($carbon as $row)
                            <label class="labeling"> <input type="radio" class="radios carbo_alt" value="{{$row->name}}" name="carbo_alternative" <?php if($row->name == $order->getCarboAlternative()){ echo 'checked'; } ?>> {{$row->name}}</label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Alamat :</label>
                    <div class="col-sm-7">
                        <div style="border:1px solid#eaeaea;width: 100%;min-height: 100px;padding: 30px;">
                            <div class="form-group" style="display: none">
                                <label class="control-label col-sm-4">Nama Alamat:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="nama_alamat" id="nama_alamat" value="{{$order->getAddressBookId()}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Alamat:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{$order->getAddress()}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Detail Alamat:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="{{$order->getDetailAddress()}}" name="detail_address">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4"></label>
                                <div class="col-sm-8">
                                    <input  type="text" name="latitude" id="lat" class="hidden" required="" value="{{$order->getLatitude()}}">
                                    <input  type="text" name="longitude" id="lng" class="hidden" required="" value="{{$order->getLongitude()}}">
                                    <div class="map" id="map">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Nama Penerima Alfternatif:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="catatan" name="catatan" value="{{$order->getCatatan()}}">
                                </div>
                            </div>
                            <div class="form-group" style="display: none">
                                <label class="control-label col-sm-4">Nomor telpon Alternatif:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="nomor_telpon" name="no_penerima" value="{{$order->getNoPenerima()}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Day for every:</label>
                                <div class="col-sm-8">
                                    <label class="labeling"> <input type="checkbox" class="radios hari"  value="Senin" name="day_for[]" <?php if(in_array('senin', $df)){ echo "checked"; } ?>> Senin</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari"  value="Selasa" name="day_for[]" <?php if(in_array('selasa', $df)){ echo "checked"; } ?>> Selasa</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari"  value="Rabu" name="day_for[]" <?php if(in_array('rabu', $df)){ echo "checked"; } ?>> Rabu</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari"  value="Kamis" name="day_for[]" <?php if(in_array('kamis', $df)){ echo "checked"; } ?>> Kamis</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari"  value="Jumat" name="day_for[]" <?php if(in_array('jumat', $df)){ echo "checked"; } ?>> Jumat</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari"  value="Sabtu" name="day_for[]" <?php if(in_array('sabtu', $df)){ echo "checked"; } ?>> Sabtu</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Kurir:</label>
                                <div class="col-sm-8">
                                    <select id="kurir" class="form-control select2" name="driver_id">
                                        <option value="">Select Kurir</option>
                                        @foreach($driver as $row)
                                            <option value="{{$row->id}}" <?php if($row->id == $order->getDriversId()->getId()){ echo 'selected'; } ?>>{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--second--}}
                <div class="form-group">
                    <label class="control-label col-sm-2">Alamat Kedua:</label>
                    <div class="col-sm-7">
                        <div style="border:1px solid#eaeaea;width: 100%;min-height: 100px;padding: 30px;">
                            <div class="form-group" style="display: none">
                                <label class="control-label col-sm-4">Nama Alamat:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="nama_alamat_second" value="{{$order->getAddressNameSecond()}}" id="nama_alamat_second">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Alamat:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="alamat_second" name="alamat_second" value="{{$order->getAddressSecond()}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Detail Alamat:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="{{$order->getDetailAddressSecond()}}" name="detail_address_second">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4"></label>
                                <div class="col-sm-8">
                                    <input  type="text" name="latitude_second" id="lat_second" class="hidden" value="{{$order->getLatitudeSecond()}}">
                                    <input  type="text" name="longitude_second" id="lng_second" class="hidden" value="{{$order->getLongitudeSecond()}}">
                                    <div class="map" id="map_second">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Nama Penerima Alfternatif:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="catatan_second" name="catatan_second" value="{{$order->getCatatanAltf()}}">
                                </div>
                            </div>
                            <div class="form-group" style="display: none">
                                <label class="control-label col-sm-4">Nomor telpon Alternatif:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="nomor_telpon_second" name="no_penerima_second" value="{{$order->getNoPenerimaSecond()}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Day for every:</label>
                                <div class="col-sm-8">
                                    <label class="labeling"> <input type="checkbox" class="radios day_for_altf"  value="Senin" name="day_for_altf[]" <?php if(in_array('senin', $dfa)){ echo "checked"; } ?>> Senin</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Selasa" name="day_for_altf[]" <?php if(in_array('selasa', $dfa)){ echo "checked"; } ?>> Selasa</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Rabu" name="day_for_altf[]" <?php if(in_array('rabu', $dfa)){ echo "checked"; } ?>> Rabu</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Kamis" name="day_for_altf[]" <?php if(in_array('kamis', $dfa)){ echo "checked"; } ?>> Kamis</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Jumat" name="day_for_altf[]" <?php if(in_array('jumat', $dfa)){ echo "checked"; } ?>> Jumat</label>
                                    <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Sabtu" name="day_for_altf[]" <?php if(in_array('sabtu', $dfa)){ echo "checked"; } ?>> Sabtu</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Kurir:</label>
                                <div class="col-sm-8">
                                    <select id="kurir_second" class="form-control select2" name="driver_id_second">
                                        <option value="">Select Kurir</option>
                                        @foreach($driver as $row)
                                            <option value="{{$row->id}}" <?php if($row->id == $order->getDriversIdSecond()){ echo 'selected'; } ?>>{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2"></label>
                    <div class="col-sm-10">
                        <a class="btn btn-default" >Cancel</a>
                        <button class="btn btn-success" type="submit" name="submit" value="save">Save</button>
                        <button class="btn btn-success" type="submit" name="submit" value="save_add">Save & add more</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('bottom')
        <?php
        $next = $order->getTglMulai();
        ?>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDKuY76FBWSQpCoO7a8GJ_h9NpdyLIEID0"></script>

        <script>

            $(document).ready(function(){
                var ttl = 0;
                $('.protein_from').click(function(){
                    if($(this).prop("checked") == true){
                        ttl += 1;
                    }else if($(this).prop("checked") == false){
                        ttl -= 1;
                    }
                    if (ttl == 0){
                        $('#protein_alternative_form').hide();
                        $('.pro_alt').attr('disabled',true).prop("checked", false);
                    }else if(ttl == 3){
                        ttl -= 1;
                        swal('Tidak dapat memilih keseluruhan');
                        $(this).prop("checked", false);
                        return false;
                    }else{
                        $('#protein_alternative_form').show();
                        $('.pro_alt').attr('disabled',false);
                    }
                });
            });

            $(document).ready(function(){
                var ttl = 0;
                $('.carbo_from').click(function(){
                    if($(this).prop("checked") == true){
                        ttl += 1;
                    }else if($(this).prop("checked") == false){
                        ttl -= 1;
                    }
                    if (ttl == 0){
                        $('#carbo_alternative_form').show();
                        $('.carbo_alt').attr('disabled',true).prop("checked", false);
                    }else if(ttl == 7){
                        ttl -= 1;
                        swal('Tidak dapat memilih keseluruhan');
                        $(this).prop("checked", false);
                        return false;
                    }else{
                        $('#carbo_alternative_form').show();
                        $('.carbo_alt').attr('disabled',false);
                    }
                });
            });



            @if($order->getVouchersCode() != NULL)
            $(document).ready(function(){
                $('#voucher_form').collapse('show');
                code = $('[name="vouchers_code"]').val();
                price = $('#price-field').val();
                $.ajax({
                    url: "{{action('AdminTrxOrdersController@getVouchers')}}?vouchers_code="+code+"&nominal="+price,
                    cache: false,
                    dataType: "json",
                    success: function(data){
                        if (data != 0) {
                            $("[name='sub_total']").val(data.sub_total);
                            $("[name='nominal_voucher']").val(data.discount);
                            $("[name='total_diskon']").val(data.total_real);
                            $("#total_diskon").val(data.total);
                        }else{
                            alert('Voucher Tidak Ditemukan!');
                            $('[name="sub_total"]').val('');
                            $('[name="nominal_voucher"]').val('');
                            $('[name="total_diskon"]').val('');
                            $("#total_diskon").val('');
                        }
                    }
                }).done(function() {

                });
            });
            @endif
            $("#voucher_form").on("hide.bs.collapse", function(){
                $("#voucher_button").html('<i class="fa fa-plus"></i> Tambah Voucher');
                $('[name="vouchers_code"]').val('');
                $('[name="sub_total"]').val('');
                $('[name="nominal_voucher"]').val('');
                $('[name="total_diskon"]').val('');
            });

            $("#voucher_form").on("show.bs.collapse", function(){
                $("#voucher_button").html('<i class="fa fa-minus"></i> Tanpa Voucher');
            });

            $("#vouchers_add").click(function() {
                code = $('[name="vouchers_code"]').val();
                price = $('#price-field').val();
                $.ajax({
                    url: "{{action('AdminTrxOrdersController@getVouchers')}}?vouchers_code="+code+"&nominal="+price,
                    cache: false,
                    dataType: "json",
                    success: function(data){
                        if (data != 0) {
                            $('.loading').show();
                            $("[name='sub_total']").val(data.sub_total);
                            $("[name='nominal_voucher']").val(data.discount);
                            $("[name='total_diskon']").val(data.total_real);
                            $("#total_diskon").val(data.total);
                        }else{
                            alert('Voucher Tidak Ditemukan!');
                            $('[name="sub_total"]').val('');
                            $('[name="nominal_voucher"]').val('');
                            $('[name="total_diskon"]').val('');
                            $("[name='total_diskon_show']").val('');
                        }
                    }
                }).done(function() {
                    setTimeout(function(){
                        $('.loading').hide();
                    }, 1000);
                });
            });

            $("input.hari").change(function() {
                if(this.checked) {
                    value = this.value;
                    $('input.hari-second:checkbox[value="' + value + '"]').prop('checked', false);
                    $('input.day_off:checkbox[value="' + value + '"]').prop('checked', false);
                }
            });

            $("input.hari-second").change(function() {
                if(this.checked) {
                    value = this.value;
                    $('input.hari:checkbox[value="' + value + '"]').prop('checked', false);
                    $('input.day_off:checkbox[value="' + value + '"]').prop('checked', false);
                }
            });

            $("input.day_off").change(function() {
                if(this.checked) {
                    value = this.value;
                    $('input.hari:checkbox[value="' + value + '"]').prop('checked', false);
                    $('input.hari-second:checkbox[value="' + value + '"]').prop('checked', false);
                }
            });

            $(".flatpick").flatpickr({
                "disable": [
                    @foreach($holiday as $row)
                        '<?php echo $row->date ?>',
                    @endforeach
                    function(date) {
                        // return true to disable
                        return (date.getDay() === 0);
                    }
                ],
                mode: "multiple",
                conjunction: " :: ",
                inline: true,
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
        </script>
        <script>
            $('.periode').on('change',function () {
                $('.loading').show();
                customer = $('#customer_id').select2().val();
                periode = $(this).val();
                type_package = $("input[name='type_package']:checked").val();
                if (customer != ''){
                    $.ajax({
                        url: "{{action('AdminTrxOrdersController@getPrice')}}?id_customer="+customer+'&periode='+periode+'&type_package='+type_package,
                        cache: false,
                        success: function(result){
                            html = '';
                            $.each( result, function( key, value ) {
                                html += '<div class="col-sm-6"><label class="labeling custom-labeling"> ' +
                                    '<input type="radio" class="radios prices" required value="'+value.id+'" data-price="'+value.real_price+'" name="packet"> ' +value.product+
                                    ' <span class="text-right">Rp. '+value.price+'</span></label></div>';
                            });
                            $('#package_list').html(html);
                        }
                    }).done(function() {
                        $('.loading').hide();
                    });
                }
            })
            $(document).on('change', '.prices', function () {
                price = $(this).attr("data-price");

                $('#price-field').val(price);
            })
            $(document).on('change', '#customer_id', function () {
                $('.loading').show();
                customer = $('#customer_id').select2().val();
                periode = $('input[name=periode]:checked').val();
                type_package = $("input[name='type_package']:checked").val();
                $.ajax({
                    url: "{{action('AdminTrxOrdersController@getPrice')}}?id_customer="+customer+'&periode='+periode+'&type_package='+type_package,
                    cache: false,
                    success: function(result){
                        html = '';
                        $.each( result, function( key, value ) {
                            html += '<div class="col-sm-6"><label class="labeling custom-labeling"> ' +
                                '<input type="radio" class="radios prices" required data-price="'+value.real_price+'" value="'+value.id+'" name="packet"> ' +value.product+
                                ' <span class="text-right">Rp. '+value.price+'</span></label></div>';
                        });
                        $('#package_list').html(html);
                    }
                }).done(function() {
                    $('.loading').hide();
                });
            })
            $(document).on('change', '.type_package', function () {
                $('.loading').show();
                customer = $('#customer_id').select2().val();
                periode = $('input[name=periode]:checked').val();
                type_package = $("input[name='type_package']:checked").val();
                $.ajax({
                    url: "{{action('AdminTrxOrdersController@getPrice')}}?id_customer="+customer+'&periode='+periode+'&type_package='+type_package,
                    cache: false,
                    success: function(result){
                        html = '';
                        $.each( result, function( key, value ) {
                            html += '<div class="col-sm-6"><label class="labeling custom-labeling"> ' +
                                '<input type="radio" class="radios prices" required data-price="'+value.real_price+'" value="'+value.id+'" name="packet"> ' +value.product+
                                ' <span class="text-right">Rp. '+value.price+'</span></label></div>';
                        });
                        $('#package_list').html(html);
                    }
                }).done(function() {
                    $('.loading').hide();
                });
            })
            function initialize() {
                var latlng = new google.maps.LatLng({{$order->getLatitude()}}, {{$order->getLongitude()}});
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
        {{--second--}}
        <script>
            function initialize2() {
                        @if($order->getLatitudeSecond() != NULL)
                var latlng = new google.maps.LatLng({{$order->getLatitudeSecond()}}, {{$order->getLongitudeSecond()}});
                        @else
                var latlng = new google.maps.LatLng(-6.189623, 106.835454);
                @endif
                    map_second = new google.maps.Map(document.getElementById('map_second'), {
                    center: latlng,
                    zoom: 17
                });
                marker_second = new google.maps.Marker({
                    map: map_second,
                    position: latlng,
                    draggable: true,
                    anchorPoint: new google.maps.Point(0, -29)
                });
                var input = document.getElementById('alamat_second');
                // map_second.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                var geocoder = new google.maps.Geocoder();
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map_second);
                var infowindow = new google.maps.InfoWindow();
                autocomplete.addListener('place_changed', function() {
                    infowindow.close();
                    marker_second.setVisible(false);
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        window.alert("Autocomplete's returned place contains no geometry");
                        return;
                    }

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map_second.fitBounds(place.geometry.viewport);
                    } else {
                        map_second.setCenter(place.geometry.location);
                        map_second.setZoom(20);
                    }

                    marker_second.setPosition(place.geometry.location);
                    marker_second.setVisible(true);

                    bindDataToFormTwo(place.formatted_address, place.geometry.location.lat(), place.geometry.location.lng());
                    infowindow.setContent(place.formatted_address);
                    infowindow.open(map, marker_second);

                });
                // this function will work on marker move event into map
                google.maps.event.addListener(marker_second, 'dragend', function() {
                    geocoder.geocode({
                        'latLng': marker_second.getPosition()
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                bindDataToFormTwo(results[0].formatted_address, marker_second.getPosition().lat(), marker_second.getPosition().lng());
                                infowindow.setContent(results[0].formatted_address);
                                infowindow.open(map_second, marker_second);
                            }
                        }
                    });
                });
            }
            function bindDataToFormTwo(address, lat, lng) {
                document.getElementById('alamat_second').value = address;
                document.getElementById('lat_second').value = lat;
                document.getElementById('lng_second').value = lng;

            }

            google.maps.event.addDomListener(window, 'load', initialize2);
        </script>
        <script>
            function autocomplete(inp, arr) {
                /*the autocomplete function takes two arguments,
                the text field element and an array of possible autocompleted values:*/
                var currentFocus;
                /*execute a function when someone writes in the text field:*/
                inp.addEventListener("input", function(e) {
                    var a, b, i, val = this.value;
                    /*close any already open lists of autocompleted values*/
                    closeAllLists();
                    if (!val) { return false;}
                    currentFocus = -1;
                    /*create a DIV element that will contain the items (values):*/
                    a = document.createElement("DIV");
                    a.setAttribute("id", this.id + "autocomplete-list");
                    a.setAttribute("class", "autocomplete-items");
                    /*append the DIV element as a child of the autocomplete container:*/
                    this.parentNode.appendChild(a);
                    /*for each item in the array...*/
                    for (i = 0; i < arr.length; i++) {
                        /*check if the item starts with the same letters as the text field value:*/
                        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                            /*create a DIV element for each matching element:*/
                            b = document.createElement("DIV");
                            /*make the matching letters bold:*/
                            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                            b.innerHTML += arr[i].substr(val.length);
                            /*insert a input field that will hold the current array item's value:*/
                            b.innerHTML += "<input class='location_list' type='hidden' value='" + arr[i] + "'>";
                            /*execute a function when someone clicks on the item value (DIV element):*/
                            b.addEventListener("click", function(e) {
                                /*insert the value for the autocomplete text field:*/
                                inp.value = this.getElementsByTagName("input")[0].value;
                                // console.log(inp.value);
                                /*close the list of autocompleted values,
                                (or any other open lists of autocompleted values:*/
                                closeAllLists();
                                id_customer =  $('#customer_id').select2().val();
                                values = inp.value;
                                $.get( "{{action('AdminTrxOrdersController@getAddress')}}?name="+values+'&id='+id_customer, function( data ) {
                                    console.log(data);
                                    $('#alamat').val(data.address);
                                    $('input[name="detail_address"]').val(data.detail_address);
                                    $('input[name="catatan"]').val(data.catatan);
                                    $('input[name="no_penerima"]').val(data.no_penerima);
                                    $('input[name="latitude"]').val(data.latitude);
                                    $('input[name="longitude"]').val(data.longitude);
                                    $("#kurir").select2().val(data.drivers_id).trigger('change.select2');
                                    marker.setPosition({lat: parseFloat(data.latitude),lng: parseFloat(data.longitude)});
                                    map.setCenter({lat: parseFloat(data.latitude),lng: parseFloat(data.longitude)});
                                    // console.log(data.address);
                                });
                            });
                            a.appendChild(b);
                        }
                    }
                });
                /*execute a function presses a key on the keyboard:*/
                inp.addEventListener("keydown", function(e) {
                    var x = document.getElementById(this.id + "autocomplete-list");
                    if (x) x = x.getElementsByTagName("div");
                    if (e.keyCode == 40) {
                        /*If the arrow DOWN key is pressed,
                        increase the currentFocus variable:*/
                        currentFocus++;
                        /*and and make the current item more visible:*/
                        addActive(x);
                    } else if (e.keyCode == 38) { //up
                        /*If the arrow UP key is pressed,
                        decrease the currentFocus variable:*/
                        currentFocus--;
                        /*and and make the current item more visible:*/
                        addActive(x);
                    } else if (e.keyCode == 13) {
                        /*If the ENTER key is pressed, prevent the form from being submitted,*/
                        e.preventDefault();
                        if (currentFocus > -1) {
                            /*and simulate a click on the "active" item:*/
                            if (x) x[currentFocus].click();
                        }
                    }
                });
                function addActive(x) {
                    /*a function to classify an item as "active":*/
                    if (!x) return false;
                    /*start by removing the "active" class on all items:*/
                    removeActive(x);
                    if (currentFocus >= x.length) currentFocus = 0;
                    if (currentFocus < 0) currentFocus = (x.length - 1);
                    /*add class "autocomplete-active":*/
                    x[currentFocus].classList.add("autocomplete-active");
                }
                function removeActive(x) {
                    /*a function to remove the "active" class from all autocomplete items:*/
                    for (var i = 0; i < x.length; i++) {
                        x[i].classList.remove("autocomplete-active");
                    }
                }
                function closeAllLists(elmnt) {
                    /*close all autocomplete lists in the document,
                    except the one passed as an argument:*/
                    var x = document.getElementsByClassName("autocomplete-items");
                    for (var i = 0; i < x.length; i++) {
                        if (elmnt != x[i] && elmnt != inp) {
                            x[i].parentNode.removeChild(x[i]);
                        }
                    }
                }
                /*execute a function when someone clicks in the document:*/
                document.addEventListener("click", function (e) {
                    closeAllLists(e.target);
                });
            }

            function autocomplete2(inp, arr) {
                /*the autocomplete function takes two arguments,
                the text field element and an array of possible autocompleted values:*/
                var currentFocus;
                /*execute a function when someone writes in the text field:*/
                inp.addEventListener("input", function(e) {
                    var a, b, i, val = this.value;
                    /*close any already open lists of autocompleted values*/
                    closeAllLists();
                    if (!val) { return false;}
                    currentFocus = -1;
                    /*create a DIV element that will contain the items (values):*/
                    a = document.createElement("DIV");
                    a.setAttribute("id", this.id + "autocomplete-list");
                    a.setAttribute("class", "autocomplete-items");
                    /*append the DIV element as a child of the autocomplete container:*/
                    this.parentNode.appendChild(a);
                    /*for each item in the array...*/
                    for (i = 0; i < arr.length; i++) {
                        /*check if the item starts with the same letters as the text field value:*/
                        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                            /*create a DIV element for each matching element:*/
                            b = document.createElement("DIV");
                            /*make the matching letters bold:*/
                            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                            b.innerHTML += arr[i].substr(val.length);
                            /*insert a input field that will hold the current array item's value:*/
                            b.innerHTML += "<input class='location_list' type='hidden' value='" + arr[i] + "'>";
                            /*execute a function when someone clicks on the item value (DIV element):*/
                            b.addEventListener("click", function(e) {
                                /*insert the value for the autocomplete text field:*/
                                inp.value = this.getElementsByTagName("input")[0].value;
                                // console.log(inp.value);
                                /*close the list of autocompleted values,
                                (or any other open lists of autocompleted values:*/
                                closeAllLists();
                                id_customer =  $('#customer_id').select2().val();
                                values = inp.value;
                                $.get( "{{action('AdminTrxOrdersController@getAddress')}}?name="+values+'&id='+id_customer, function( data ) {
                                    console.log(data);
                                    $('#alamat_second').val(data.address);
                                    $('input[name="detail_address_second"]').val(data.detail_address);
                                    $('input[name="catatan_second"]').val(data.catatan);
                                    $('input[name="no_penerima_second"]').val(data.no_penerima);
                                    $('input[name="latitude_second"]').val(data.latitude);
                                    $('input[name="longitude_second"]').val(data.longitude);
                                    $("#kurir_second").select2().val(data.drivers_id).trigger('change.select2');
                                    marker_second.setPosition({lat: parseFloat(data.latitude),lng: parseFloat(data.longitude)});
                                    map_second.setCenter({lat: parseFloat(data.latitude),lng: parseFloat(data.longitude)});
                                    // console.log(data.address);
                                });
                            });
                            a.appendChild(b);
                        }
                    }
                });
                /*execute a function presses a key on the keyboard:*/
                inp.addEventListener("keydown", function(e) {
                    var x = document.getElementById(this.id + "autocomplete-list");
                    if (x) x = x.getElementsByTagName("div");
                    if (e.keyCode == 40) {
                        /*If the arrow DOWN key is pressed,
                        increase the currentFocus variable:*/
                        currentFocus++;
                        /*and and make the current item more visible:*/
                        addActive(x);
                    } else if (e.keyCode == 38) { //up
                        /*If the arrow UP key is pressed,
                        decrease the currentFocus variable:*/
                        currentFocus--;
                        /*and and make the current item more visible:*/
                        addActive(x);
                    } else if (e.keyCode == 13) {
                        /*If the ENTER key is pressed, prevent the form from being submitted,*/
                        e.preventDefault();
                        if (currentFocus > -1) {
                            /*and simulate a click on the "active" item:*/
                            if (x) x[currentFocus].click();
                        }
                    }
                });
                function addActive(x) {
                    /*a function to classify an item as "active":*/
                    if (!x) return false;
                    /*start by removing the "active" class on all items:*/
                    removeActive(x);
                    if (currentFocus >= x.length) currentFocus = 0;
                    if (currentFocus < 0) currentFocus = (x.length - 1);
                    /*add class "autocomplete-active":*/
                    x[currentFocus].classList.add("autocomplete-active");
                }
                function removeActive(x) {
                    /*a function to remove the "active" class from all autocomplete items:*/
                    for (var i = 0; i < x.length; i++) {
                        x[i].classList.remove("autocomplete-active");
                    }
                }
                function closeAllLists(elmnt) {
                    /*close all autocomplete lists in the document,
                    except the one passed as an argument:*/
                    var x = document.getElementsByClassName("autocomplete-items");
                    for (var i = 0; i < x.length; i++) {
                        if (elmnt != x[i] && elmnt != inp) {
                            x[i].parentNode.removeChild(x[i]);
                        }
                    }
                }
                /*execute a function when someone clicks in the document:*/
                document.addEventListener("click", function (e) {
                    closeAllLists(e.target);
                });
            }
        </script>
        <script>
            var periode = $('input[name="periode"]:checked').val();
            $('form').on('submit',function () {
                var string = $('[name="date_start"]').val();
                var array = string.split(' :: ');
                if (string.length == 0){
                    ttl = 0;
                }else{
                    ttl = array.length;
                }
                console.log(ttl);
                if (ttl > periode){
                    swal("Total hari yang anda pilih melebihi batas maksimal");
                    return false;
                }else if(ttl < periode){
                    swal("Total hari yang anda pilih kurang dari jumlah seharusnya");
                    return false;
                }else{
                    return true;
                }
            });
        </script>
    @endpush
@endsection