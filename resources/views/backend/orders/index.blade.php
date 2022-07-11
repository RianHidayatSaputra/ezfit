@extends('crudbooster::layouts.layout')
@section('content')
    <style>
        table.dataTable thead .sorting_desc, table.dataTable thead .sorting_asc, table.dataTable thead .sorting{
            background-image : none !important;
        }
        table.dataTable thead .no-sort.sorting_asc:after{
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset('DataTables/datatables.min.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
    <p>
        <a href="{{url('admin/trx_orders/add?status')}}={{g('status')}}" class="btn btn-success btn-xs">Add Order</a>
        {{--<a href="{{url('admin/trx_orders/import?status')}}={{g('status')}}" class="btn btn-primary btn-xs">Import</a>--}}
        <a href="{{url('admin/trx_orders/alergen?status')}}={{g('status')}}" class="btn btn-warning btn-xs">Check Alergen</a>
    </p>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Browser Data</h3>
        </div>
        <div class="box-body">
            <table id="table" class="display responsive nowrap" width="100%">
                <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th class="no-sort">Action</th>
                    <th>No Order</th>
                    <th>Nama Customer</th>
                    <th>Tgl Mulai</th>
                    <th>Type Package</th>
                    <th>Package</th>
                    <th class="no-sort">Days Left</th>
                    <th class="no-sort">Days Used</th>
                    <th>Sub Total</th>
                    <th>Discount</th>
                    <th>Kode Voucher</th>
                    <th>Total</th>
                    <th>Metode Pembayaran</th>
                    <th>Status Pembayaran</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    @push('bottom')
        <script src="{{asset('DataTables/datatables.min.js')}}"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    stateSave: true,
                    "ajax": '{{url("admin/trx_orders/json")}}/{{g('status')}}',
                    "columnDefs": [ {
                        "targets": 'no-sort',
                        "orderable": false,
                    },{
                        "targets": 6,
                        "className": "text-center",
                    },{
                        "targets": 4,
                        "className": "text-center",
                    },{
                        "targets": 5,
                        "className": "text-center",
                    },{
                        "targets": 7,
                        "className": "text-center",
                    },{
                        "targets": 8,
                        "className": "text-center",
                    } ]
                });
            } );
        </script>
        <script>
            function deleteConfirmation(url)
            {
                showConfirmation("Are you sure?", "Delete the data can't be undone", () => {
                    location.href = url
                })
            }

            function goToUrlWithConfirmation(url, message)
            {
                showConfirmation("Are you sure?", message, () => {
                    location.href = url
                })
            }
        </script>
    @endpush
@endsection