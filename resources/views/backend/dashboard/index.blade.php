@extends('crudbooster::layouts.layout')
@section('content')
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $order_success_today }}</h3>
                <p>Jumlah Total Order</p>
            </div>
            <div class="icon">
                <i class="fa fa-smile-o"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $delivery_today }}<sup style="font-size: 20px"></sup></h3>
                <p>Pengiriman Hari Ini</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="box box-success customBox">
            <div class="box-header">
                <div class="pull-right box-tools">
                    <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-danger btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                    <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-danger btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
                </div>
                <i class="fa fa-user"></i>

                <h3 class="box-title">Latest New Users</h3>
            </div>


            <div class="box-body">
                <table class="table table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Type</th>
                        </tr>
                        @foreach($latest_users as $l)
                        <tr>
                            <td>{{ $l->name }}</td>
                            <td>{{ $l->email }}</td>
                            <td>{{ $l->ho_hp }}</td>
                            <td>
                                @if($l->type_customer != 'mahasiswa')
                                <label class="label label-primary">Umum</label>
                                @else
                                <label class="label label-success">Mahasiswa</label>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="box-footer">

            </div>
        </div>
        <div class="box box-primary customBox">
            <div class="box-header">
                <div class="pull-right box-tools">
                    <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-danger btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                    <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-danger btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
                </div>
                <i class="fa fa-user"></i>

                <h3 class="box-title">Request Mahasiswa</h3>
            </div>


            <div class="box-body">
                <table class="table table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Photo KRS</th>
                        </tr>
                        @foreach($request_mahasiswa as $r)
                        <tr>
                            <td>{{ $r->name }}</td>
                            <td>{{ $r->email }}</td>
                            <td>{{ $r->ho_hp }}</td>
                            <td><a data-lightbox="preview-image" title="Preview Image" href="{{ asset($r->photo_krs) }}">
                                <img src="{{ asset($r->photo_krs) }}" style="height: 50px" alt="Photo KRS">
                            </a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="box-footer">

            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="box box-warning customBox">
            <div class="box-header">
                <div class="pull-right box-tools">
                    <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-danger btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                    <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-danger btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
                </div>
                <i class="fa fa-user"></i>

                <h3 class="box-title">Order Confirmation</h3>
            </div>


            <div class="box-body">
                <table class="table table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th>No Order</th>
                            <th>Nama Customer</th>
                            <th>Tgl Mulai</th>
                            <th>Package</th>
                            <th>Days Left</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                        </tr>
                        @foreach($request_orders as $o)
                        <tr>
                            <td>{{ $o->no_order }}</td>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->tgl_mulai }}</td>
                            <td>{{ $o->packages }}</td>
                            <td>{{ $o->days_left }}</td>
                            <td>Rp.{{ number_format($o->total) }}</td>
                            <td>
                            @php
                                $link = action('AdminTrxOrdersController@getUpdateStatus').'?id='.$o->id;
                                if($o->status_payment == 'Waiting Payment'){
                                    $status = 'Confirmation';
                                }else{
                                    $status = 'Success Payment';
                            }
                            @endphp
                            <div class='btn-group'>
                                <button type='button' class='btn btn-xs btn-warning dropdown-toggle' data-toggle='dropdown'>
                                    {{ $o->status_payment}} <span class='caret'></span></button>
                                    <ul class='dropdown-menu' role='menu'>
                                        <li><a href='{{ $link }}&status={{ $status }}'><small>Confirmation</small></a></li>
                                        <li><a href='{{ $link }}&status=Failed'><small>Cancel / Failed</small></a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="box-footer">

            </div>
        </div>
    </div>
</div>
@endsection