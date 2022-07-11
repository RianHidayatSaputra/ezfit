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
            <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{action('AdminPackagesController@postAddSave')}}">
            {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-sm-2">Nama Price List:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Masukan Price List" name="name" value="{{old('name')}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Photo:</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control"  name="photo" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Type Package:</label>
                    <div class="col-sm-10">
                        <label class="labeling"> <input type="radio" name="type_package" value="Regular"> Regular </label>
                        <label class="labeling"> <input type="radio" name="type_package" value="Propack"> Propack </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Tipe produk:</label>
                    <div class="col-sm-10">
                        @foreach($type_product as $row)
                            <label class="labeling"> <input type="checkbox" value="{{$row->name}}" class="radios" name="category[]"> {{$row->name}}</label>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Harga Umum 1 hari:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_u1" placeholder="Harga dengan discount" required value="{{old('price_u1')}}" >
                            </div>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_uh1" placeholder="Harga tanpa discount" required value="{{old('price_uh1')}}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Harga Umum 6 hari:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_u2" placeholder="Harga dengan discount" required value="{{old('price_u2')}}" >
                            </div>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_uh2" placeholder="Harga tanpa discount" required value="{{old('price_uh2')}}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Harga Umum 24 hari:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_u3" placeholder="Harga dengan discount" required value="{{old('price_u3')}}" >
                            </div>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_uh3" placeholder="Harga tanpa discount" required value="{{old('price_uh3')}}" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Harga Mahasiswa 1 hari:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_m1" placeholder="Harga dengan discount"  required value="{{old('price_m1')}}" >
                            </div>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_mh3" placeholder="Harga tanpa discount"  required value="{{old('price_mh3')}}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Harga Mahasiswa 6 hari:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_m2" placeholder="Harga dengan discount"  required value="{{old('price_m2')}}" >
                            </div>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_mh3" placeholder="Harga tanpa discount"  required value="{{old('price_mh3')}}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Harga Mahasiswa 24 hari:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_m3" placeholder="Harga dengan discount"  required value="{{old('price_m3')}}" >
                            </div>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="price_mh3" placeholder="Harga tanpa discount"  required value="{{old('price_mh3')}}" >
                            </div>
                        </div>
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


@endsection