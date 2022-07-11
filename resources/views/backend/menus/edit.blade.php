<?php
/** @var $menu \App\Models\Menus */
$alergy = json_decode($menu->getAlergy());
if ($alergy) {
    foreach ($alergy as $row){
        $arr[] = $row->alergy;
    }
}else{
    $arr = [];
}

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
</style>
<div class="box box-default">
    <div class="box-body">
        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{action('AdminMenusController@postEditSave')}}/{{$menu->getId()}}">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-sm-2">Nama Menu:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Masukan nama menu" name="name" value="{{$menu->getName()}}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Disediakan tanggal:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control flatpick" placeholder="Masukan tanggal disediakan" value="{{$menu->getMenuDate()}}" name="date_product" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Alergen:</label>
                <div class="col-sm-10">
                    @foreach($master_alergy as $row)
                    @if(in_array($row->name, $arr))
                    <label class="labeling"> <input type="checkbox" checked  value="{{$row->name}}" name="alergy[]"> {{$row->name}} <small>{{$row->detail}}</small></label>
                    @else
                    <label class="labeling"> <input type="checkbox"  value="{{$row->name}}" name="alergy[]"> {{$row->name}} <small>{{$row->detail}}</small></label>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Kandungan protein dari:</label>
                <div class="col-sm-10">
                    @foreach($protein as $row)
                    <label class="labeling"> <input type="radio" class="radios"  <?php if($menu->getProteinFrom() == $row->name){ echo "checked"; } ?>  value="{{$row->name}}" name="protein_from"> {{$row->name}}</label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Kandungan Carbs dari:</label>
                <div class="col-sm-10">
                    @foreach($carbon as $row)
                    <label class="labeling"> <input type="radio" class="radios"  <?php if($menu->getCarboFrom() == $row->name){ echo "checked"; } ?>  value="{{$row->name}}" name="carbo_from"> {{$row->name}}</label>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <h3>Regular</h3>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Protein :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" type="text" class="form-control" name="protein" onkeyup="count_calory()" required value="{{ $menu->getProtein() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Gula :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" type="text" class="form-control" name="gula" required value="{{ $menu->getGula() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Carbs :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" type="text" onkeyup="count_calory()" class="form-control" name="carbo" required value="{{ $menu->getCarbo() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Saturated Fat :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" type="text" class="form-control" name="saturated_fat" required value="{{ $menu->getSaturatedFat() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Lemak :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" onkeyup="count_calory()" type="text" class="form-control" name="fat" required value="{{ $menu->getFat() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Kalori :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input readonly="" id="number" type="text" class="form-control" name="calory" required value="{{ $menu->getCalory() }}" >
                                        <span class="input-group-addon">KKal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h3>Propack</h3>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Protein :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input onkeyup="count_calory_p()" id="number" type="text" class="form-control" name="protein_p" value="{{ $menu->getProteinP() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Gula :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" type="text" class="form-control" name="gula_p" value="{{ $menu->getGulaP() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Carbs :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input onkeyup="count_calory_p()" id="number" type="text" class="form-control" name="carbo_p" value="{{ $menu->getCarboP() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Saturated Fat :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" type="text" class="form-control" name="saturated_fat_p" value="{{ $menu->getSaturatedFatP() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Lemak :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input onkeyup="count_calory_p()" id="number" type="text" class="form-control" name="fat_p" value="{{ $menu->getFatP() }}" >
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Kalori :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" type="text" class="form-control" name="calory_p" readonly="" value="{{ $menu->getCaloryP() }}" >
                                        <span class="input-group-addon">KKal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Tipe produk:</label>
                <div class="col-sm-10">
                    @foreach($type_product as $row)
                    <label class="labeling"> <input type="radio" value="{{$row->name}}" <?php if($menu->getProductId() == $row->name){ echo "checked"; } ?> class="radios" name="product_id" required> {{$row->name}}</label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Estimasi Hpp:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Masukan Estimasi Hpp" name="price_hpp" required value="{{$menu->getPriceHpp()}}" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Estimasi Hpp (Propack):</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Masukan Estimasi Hpp (Propack)" name="price_hpp_p" value="{{$menu->getPriceHppP()}}" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Photo:</label>
                <div class="col-sm-3">
                    <input type="file" class="form-control" placeholder="photo" name="photo">
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
        minDate : "today",
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });
</script>
<script>
    function count_calory() {
        protein = $("input[name='protein']").val();
        carbo = $("input[name='carbo']").val();
        lemak = $("input[name='fat']").val();

        total = (protein * 4) + (carbo * 4) + (lemak * 9);
        result = Math.round(total * 100) / 100;
        $("input[name='calory']").val(result);
    }

    function count_calory_p() {
        protein = $("input[name='protein_p']").val();
        carbo = $("input[name='carbo_p']").val();
        lemak = $("input[name='fat_p']").val();

        total = (protein * 4) + (carbo * 4) + (lemak * 9);
        result = Math.round(total * 100) / 100;
        $("input[name='calory_p']").val(result);
    }
</script>
@endpush
@endsection