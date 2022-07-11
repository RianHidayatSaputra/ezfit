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
        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{action('AdminMenusController@postSave')}}">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-sm-2">Nama Menu:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Masukan nama menu" name="name" value="{{old('name')}}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Disediakan tanggal:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control flatpick" placeholder="Masukan tanggal disediakan" value="{{old('date_product')}}" name="date_product" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Alergen:</label>
                <div class="col-sm-10">
                    @foreach($master_alergy as $row)
                    <label class="labeling"> <input type="checkbox"  value="{{$row->name}}" name="alergy[]"> {{$row->name}} <small>{{$row->detail}}</small></label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Kandungan protein dari:</label>
                <div class="col-sm-10">
                    @foreach($protein as $row)
                    <label class="labeling"> <input type="radio" class="radios" value="{{$row->name}}" name="protein_from"> {{$row->name}}</label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Kandungan Carbs dari:</label>
                <div class="col-sm-10">
                    @foreach($carbon as $row)
                    <label class="labeling"> <input type="radio" class="radios" value="{{$row->name}}" name="carbo_from"> {{$row->name}}</label>
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
                                        <input id="number" type="text" class="form-control" name="protein" onkeyup="count_calory()" required value="{{ old('protein') }}">
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
                                        <input id="number" type="text" class="form-control" name="gula" required value="{{old('gula')}}" >
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
                                        <input id="number" type="text" class="form-control" name="carbo" required onkeyup="count_calory()" value="{{old('carbo')}}" >
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
                                        <input id="number" type="text" class="form-control" name="saturated_fat" required value="{{old('saturated_fat')}}" >
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
                                        <input id="number" type="text" class="form-control" name="fat" required value="{{old('fat')}}" onkeyup="count_calory()">
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Kalori:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" readonly="" type="text" class="form-control" name="calory" required value="{{old('calory')}}" >
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
                                        <input id="number" type="text" class="form-control" name="protein_p" onkeyup="count_calory_p()" value="{{old('protein_p')}}" >
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
                                        <input id="number" type="text" class="form-control" name="gula_p" value="{{old('gula_p')}}">
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
                                        <input id="number" type="text" class="form-control" name="carbo_p" onkeyup="count_calory_p()" value="{{old('carbo_p')}}">
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
                                        <input id="number" type="text" class="form-control" name="saturated_fat_p" value="{{old('saturated_fat_p')}}" >
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
                                        <input id="number" type="text" class="form-control" name="fat_p" value="{{old('fat_p')}}" onkeyup="count_calory_p()">
                                        <span class="input-group-addon">gr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-5">Kalori:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="number" type="text" class="form-control" name="calory_p" readonly="" value="{{old('calory_p')}}" >
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
                    <label class="labeling"> <input type="radio" value="{{$row->name}}" class="radios" name="product_id" required> {{$row->name}}</label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Estimasi Hpp:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Masukan Estimasi Hpp" name="price_hpp" required value="{{old('price_hpp')}}" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Estimasi Hpp (Propack):</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Masukan Estimasi Hpp (Propack)" name="price_hpp_p" value="{{old('price_hpp_p')}}" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Photo:</label>
                <div class="col-sm-3">
                    <input type="file" class="form-control" placeholder="photo" name="photo" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-10">
                    <a class="btn btn-default" >Cancel</a>
                    <button class="btn btn-success" name="submit" value="save">Save</button>
                    <button class="btn btn-success" name="submit" value="save_add">Save & add more</button>
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