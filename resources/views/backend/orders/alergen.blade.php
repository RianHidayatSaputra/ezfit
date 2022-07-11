@extends('crudbooster::layouts.layout')
@section('content')
    <div class="box box-default">
        <div class="box-body">
            <table class="table table-striped table-boredered">
                <tr>
                    <th>Alergen</th>
                    <th>Total</th>
                </tr>
                @foreach($data as $row)
                    <?php
                    $total = DB::table('trx_orders_alergy')->where('master_alergy_id',$row->id)->count();
                    ?>
                    <tr>
                        <td>{{$row->name}}</td>
                        <td>{{$total}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection