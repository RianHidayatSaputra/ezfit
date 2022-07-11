@extends('crudbooster::layouts.layout')
@section('content')
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Import Data</h3>
        </div>
        <!-- /.box-header -->
        <form class="form-horizontal" action="{{action('AdminTrxOrdersController@postImport')}}" method="post"  enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="callout callout-info">
                    <h4>Perhatian</h4>
                    <ol>
                        <li>Download Format : <a href="{{asset('sample/import order.xls')}}" download="" >disini</a></li>
                        <li>Pastikan format tanggal sesuai</li>
                        <li>Pastikan untuk hari tidak menggunakan huruf kapital</li>
                        <li>Sebaiknya untuk kosong diisi dengan kosong saja tidak dengan tanda (x)</li>
                    </ol>
                </div>
                <div class="form-group">
                    <label for="file" class="col-sm-2 control-label">File</label>

                    <div class="col-sm-10">
                        <input type="file" name="file" class="form-control" required id="file" placeholder="Upload File" required>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-info pull-right">Import File</button>
            </div>
        </form>
        <!-- /.box-body -->
    </div>
@endsection