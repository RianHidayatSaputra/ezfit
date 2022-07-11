@extends('crudbooster::layouts.layout')
@section('content')
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Question : </h3>
        </div>
        <form action="{{action('AdminQuestionController@postAddSave')}}" class="form-horizontal" method="post">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Question</label>

                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" placeholder="masukan pertanyaan">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Hint</label>

                    <div class="col-sm-10">
                        <input type="content" class="form-control" placeholder="masukan hint">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type Pertanyaan</label>

                    <div class="col-sm-10">
                        <select name="type_question" id="type_question" class="form-control">
                            <option value="multiple choices">Multiple Choices</option>
                            <option value="gender choices">Gender Choices</option>
                            <option value="date picker">Date Picker</option>
                            <option value="form">Form</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Next Button</label>
                    <div class="col-sm-10">
                        <select name="show_button_next" id="show_button_next" class="form-control">
                            <option value="true">Show</option>
                            <option value="false">Hidden</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="multiple">
                            <button type="button" id="multiple" class="btn btn-success pull-right">Add Choise</button>
                            <div style="clear: both"></div>
                            <table class="table table-bordered" style="margin-top: 10px;">
                                <thead>
                                <tr>
                                    <th>Jawaban</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                                </thead>
                                <tbody id="value_multiple">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row hidden">
                    <div class="col-sm-12">
                        <div class="gender">
                            <button type="button" id="gender" class="btn btn-success pull-right">Add Choise</button>
                        </div>
                    </div>
                </div>
                <div class="row hidden">
                    <div class="col-sm-12">
                        <div class="form">
                            <button type="button" id="form" class="btn btn-success pull-right">Add Choise</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
        </form>
    </div>
    @push('bottom')
        <script>
            function Removed(id){
                $('#c'+id).remove();
            }
            $( document ).ready(function() {
                $('#multiple').on('click',function () {
                    id = Math.floor((Math.random() * 10000) + 1);
                    html = '<tr id="c'+id+'">' +
                        '<td><input class="form-control" name="choise[]"></td>' +
                        '<td class="text-right"><button type="button" class="btn btn-xs btn-danger" onclick="Removed('+id+')"><i class="fa fa-trash"></i></button>' +
                        '</tr>';

                    $( html ).prependTo( "#value_multiple" );
                });
            });
        </script>
    @endpush
@endsection