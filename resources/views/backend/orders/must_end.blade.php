<?php
/** @var $row \App\Models\TrxOrders */
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
            <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{action('AdminTrxOrdersController@postEditMustEnd')}}/{{$row->getId()}}">
                {{ csrf_field() }}
                <input type="text" name="status_url" value="{{ g('status') }}" hidden="">
                <div class="form-group">
                    <label class="control-label col-sm-2">Date Must End:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control flatpick" name="must_end" data-default-date="{{ $row->getMustEnd() }}" value="{{ $row->getMustEnd() }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2"></label>
                    <div class="col-sm-10">
                        <a class="btn btn-default" >Cancel</a>
                        <button class="btn btn-success" type="submit" name="submit" value="save">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('bottom')
        <?php
            $next = $row->getMustEnd();
        ?>
        <script>
            $(".flatpick").flatpickr({
                "disable": [
                    function(date) {
                        return (date.getDay() === 0);
                    }
                ],
                altInput: true,
                altFormat: "F j, Y",
                defaultDate: "{{ $next }}",
            });
        </script>
    @endpush
@endsection