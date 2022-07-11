@extends('crudbooster::layouts.layout')
@section('content')
    <?php
        $holiday = DB::table('holidays')->get();
    ?>
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
    <div class="overlay loading">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>
    <link rel="stylesheet" href="{{asset('css/loading.css')}}">
    <div class="box box-default">
        <div class="box-body">
            <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{action('AdminTrxOrdersController@postAddSave')}}" id="form">
                {{ csrf_field() }}
                <input type="text" name="status_url" value="{{ g('status') }}" hidden="">
                <div class="form-group">
                    <label class="control-label col-sm-2">Customer:</label>
                    <div class="col-sm-4">
                        <select id="customer_id" name="customer_id" class="form-control select2">
                            <option value="">Select Customer</option>
                            @foreach($customer as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Pilih Type Package :</label>
                    <div class="col-sm-10">
                        <label class="labeling"> <input type="radio" class="radios type_package" required checked value="Regular" name="type_package"> Regular</label>
                        <label class="labeling"> <input type="radio" class="radios type_package" required value="Propack" name="type_package"> Propack</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Pilih Periode :</label>
                    <div class="col-sm-10">
                        <label class="labeling"> <input type="radio" class="radios periode" required value="1" name="periode" checked> 1 Hari</label>
                        <label class="labeling"> <input type="radio" class="radios periode" required value="6" name="periode"> 6 Hari (save 1)</label>
                        <label class="labeling"> <input type="radio" class="radios periode" required value="24" name="periode"> 24 hari (save 30%-50%)</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Pilih Package :</label>
                    <div class="col-sm-10">
                        <div class="row" id="package_list">

                        </div>
                    </div>
                </div>
                <input type="text" name="price" id="price-field" style="display: none;">
                <div class="form-group" id="voucher_button_form" hidden="">
                    <label class="control-label col-sm-2">Voucher:</label>
                    <div class="col-sm-3">
                        <button class="btn btn-success" type="button" id="voucher_button" data-toggle="collapse" data-target="#voucher_form" aria-expanded="false" aria-controls="voucher_form"><i class="fa fa-plus"></i> Tambah Voucher</button>
                    </div>
                </div>
                <div id="voucher_form" hidden="">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Kode Voucher:</label>
                        <div class="col-sm-3">
                            <input type="text" name="vouchers_code" class="form-control"/>
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
                        <label class="labeling"> <input type="radio" class="radios" required value="direct transfer" name="metode_payment"> Direct Transfer</label>
                        <label class="labeling"> <input type="radio" class="radios" required value="virtual account" name="metode_payment"> Virtual Account</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Date:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control flatpick" name="date_start" value="{{old('date')}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Alergen:</label>
                    <div class="col-sm-10">
                        @foreach($master_alergy as $row)
                            <label class="labeling"> <input type="checkbox"  value="{{$row->id}}" name="alergy[]"> {{$row->name}} <small>{{$row->detail}}</small></label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Kandungan protein yang dihindari:</label>
                    <div class="col-sm-9">
                        @foreach($protein as $row)
                            <label class="labeling"> <input type="checkbox" class="protein_from" value="{{$row->name}}" name="protein_from[]"> {{$row->name}}</label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group" id="protein_alternative_form" hidden="">
                    <label class="control-label col-sm-3">Kandungan protein Alternatif:</label>
                    <div class="col-sm-9">
                        @foreach($protein as $row)
                            <label class="labeling"> <input type="checkbox" class="radios" value="{{$row->name}}" name="protein_alternative"> {{$row->name}}</label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Kandungan Carbs yang dihindari:</label>
                    <div class="col-sm-9">
                        @foreach($carbon as $row)
                            <label class="labeling"> <input type="checkbox" class="radios carbo_from" value="{{$row->name}}" name="carbo_from[]"> {{$row->name}}</label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group" id="carbo_alternative_form" hidden="">
                    <label class="control-label col-sm-3">Kandungan Carbs Alternatif</label>
                    <div class="col-sm-9">
                        @foreach($carbon as $row)
                            <label class="labeling"> <input type="checkbox" class="radios" value="{{$row->name}}" name="carbo_alternative"> {{$row->name}}</label>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Alamat :</label>
                        <div class="col-sm-7">
                            <div style="border:1px solid#eaeaea;width: 100%;min-height: 100px;padding: 30px;">
                                <div class="form-group" style="display: none">
                                    <label class="control-label col-sm-4">Nama Alamat:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="nama_alamat" value="-" id="nama_alamat">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Alamat:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="alamat" name="alamat">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Detail Alamat:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="" name="detail_address">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4"></label>
                                    <div class="col-sm-8">
                                        <input  type="text" name="latitude" id="lat" class="hidden" required="">
                                        <input  type="text" name="longitude" id="lng" class="hidden" required="">
                                        <div class="map" id="map">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Nama Penerima Alfternatif:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="catatan" name="catatan">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Day for every:</label>
                                    <div class="col-sm-8">
                                        <label class="labeling"> <input type="checkbox" class="radios hari" checked value="Senin" name="day_for[]"> Senin</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari" checked value="Selasa" name="day_for[]"> Selasa</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari" checked value="Rabu" name="day_for[]"> Rabu</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari" checked value="Kamis" name="day_for[]"> Kamis</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari" checked value="Jumat" name="day_for[]"> Jumat</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari" checked value="Sabtu" name="day_for[]"> Sabtu</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Kurir:</label>
                                    <div class="col-sm-8">
                                        <select id="kurir" class="form-control select2" name="driver_id">
                                            <option value="">Select Kurir</option>
                                            @foreach($driver as $row)
                                                <option value="{{$row->id}}">{{$row->name}}</option>
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
                                        <input type="text" class="form-control" name="nama_alamat_second" value="-" id="nama_alamat_second">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Alamat:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="alamat_second" name="alamat_second">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Detail Alamat:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="" name="detail_address_second">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4"></label>
                                    <div class="col-sm-8">
                                        <input  type="text" name="latitude_second" id="lat_second" class="hidden">
                                        <input  type="text" name="longitude_second" id="lng_second" class="hidden">
                                        <div class="map" id="map_second">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Nama Penerima Alfternatif:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="catatan_second" name="catatan_second">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Day for every:</label>
                                    <div class="col-sm-8">
                                        <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Senin" name="day_for_altf[]"> Senin</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Selasa" name="day_for_altf[]"> Selasa</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Rabu" name="day_for_altf[]"> Rabu</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Kamis" name="day_for_altf[]"> Kamis</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Jumat" name="day_for_altf[]"> Jumat</label>
                                        <label class="labeling"> <input type="checkbox" class="radios hari-second"  value="Sabtu" name="day_for_altf[]"> Sabtu</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Kurir:</label>
                                    <div class="col-sm-8">
                                        <select id="kurir_second" class="form-control select2" name="driver_id_second">
                                            <option value="">Select Kurir</option>
                                            @foreach($driver as $row)
                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
        $next = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));
        ?>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDKuY76FBWSQpCoO7a8GJ_h9NpdyLIEID0"></script>

        <script>
            $('.protein_from').on('change',function () {
                $('#protein_alternative_form').show();
            });

            $('input[name="protein_alternative"]').on('change',function () {
                var from = $("input[name='protein_from']:checked").val(),
                    to = $("input[name='protein_alternative']:checked").val();

                if (from == to) {
                    alert('Protein Alternatif Tidak Boleh Sama Dengan Protein Yang Dihindari!');
                    $(this).prop("checked", false);
                }
            });

            $('input[name="carbo_alternative"]').on('change',function () {
                var from = $("input[name='carbo_from']:checked").val(),
                    to = $("input[name='carbo_alternative']:checked").val();

                if (from == to) {
                    alert('Carbo Alternatif Tidak Boleh Sama Dengan Carbo Yang Dihindari!');
                    $(this).prop("checked", false);
                }
            });

            $('.carbo_from').on('change',function () {
                $('#carbo_alternative_form').show();
            });

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
                $("input[name='prolang']:checked").each(function(){
                    lang.push(this.value);
                });
                $.ajax({
                    url: "{{action('AdminTrxOrdersController@getVouchers')}}?vouchers_code="+code+"&nominal="+price+"&customer_id="+customer,
                    cache: false,
                    dataType: "json",
                    success: function(data){
                        if (data.ajax_status != 0) {
                            $('.loading').show();
                            $("[name='sub_total']").val(data.sub_total);
                            $("[name='nominal_voucher']").val(data.discount);
                            $("[name='total_diskon']").val(data.total_real);
                            $("#total_diskon").val(data.total);
                        }else{
                            alert(data.ajax_message);
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

            $('#form').on('submit',function () {
                second = $('#alamat_second').val();
                checkbox1 = $('input.hari:checked').length;
                checkbox2 = $('input.hari-second:checked').length;
                checkbox3 = $('input.day_off:checked').length;
                if (second.length > 1){
                    if (parseInt(checkbox2) < 1){
                        swal('Tentukan hari apa saja untuk alamat kedua');
                        return false;
                    }
                    ttl = parseInt(checkbox1)+parseInt(checkbox2)+parseInt(checkbox3);

                    if (ttl != 6){
                        swal('Pastikan semua hari terbagi kedalam 2 alamat');
                        return false;
                    }
                }else{
                    ttl = parseInt(checkbox1)+parseInt(checkbox3);
                    if (ttl != 6){
                        swal('Mohon untuk sesuaikan checklist antara alamat pertama dan hari libur');
                        return false;
                    }
                }


            })
            $(".flatpick").flatpickr({
                mode: "multiple",
                conjunction: " :: ",
                inline: true,
                "disable": [
                    @foreach($holiday as $row)
                        '<?php echo $row->date ?>',
                    @endforeach
                    function(date) {
                        // return true to disable
                        return (date.getDay() === 0);

                    }
                ],
                altInput: true,
                minDate : "<?php echo $next; ?>",
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
        </script>
        <script>
            $('.periode').on('change',function () {
                $('.loading').show();
                customer = $('#customer_id').select2().val();
                periode = $(this).val();
                // console.log('periode');
                // console.log(periode);
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
                $('#voucher_button_form').show();
            })
            $(document).on('change', '#customer_id', function () {
                $('.loading').show();
                customer = $('#customer_id').select2().val();
                periode = $('input[name=periode]:checked').val();
                // console.log('customer');
                // console.log(periode);
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
                // console.log('type_package');
                // console.log(periode);
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
        {{--second--}}
        <script>
            function initialize2() {
                var latlng = new google.maps.LatLng(-6.189623, 106.835454);
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
            var periode = $('[name="periode"]').val();
            $('form').on('submit',function () {
                var string = $('[name="date_start"]').val();
                var array = string.split(' :: ');
                if (string.length == 0){
                    ttl = 0;
                }else{
                    ttl = array.length;
                }
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