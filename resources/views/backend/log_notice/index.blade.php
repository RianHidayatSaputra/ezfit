@extends('crudbooster::layouts.layout')
@section('content')
<style>
	.box-solid{
		box-shadow: 0 1px 5px rgba(0, 0, 0, 0.16);
	}
	#map{
		height: 270px;
		margin-bottom: 30px;
	}
	.select2-container {
		width: 100% !important;
		padding: 0;
	}
	.form-control-static{
		text-align: right;
	}
	#selesai{
		display: none;
	}
	.box-solid{
		background: #fff;
	}
	table.dataTable thead .no-sort.sorting_asc:after{
		display: none !important;
	}
</style>
<div class="overlay loading">
	<div class="overlay__inner">
		<div class="overlay__content"><span class="spinner"></span></div>
	</div>
</div>
<link rel="stylesheet" href="{{asset('css/loading.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
<div class="row">
	<div class="col-sm-12">
		<div class="box box-default">
			<div class="box-body table-responsive no-padding">
				<table class='table table-bordered'>
					<tbody>
						<tr>
							<td width="25%">
								<strong>
									Bulk Change Status
								</strong>
							</td>
							<td>
								<div class="row">
									<div class="col-md-5">
										<select readonly id="bulk-act" class="form-control" name="bulk-act">
											<option selected="">Is Read</option>
										</select>
									</div>
									<div class="col-md-4">
										<button style="margin-right: 12px;" class="btn btn-primary btn-bulk"><i class="fa fa-telegram"></i> Submit</button>
										<a class="btn btn-success" href="{{ action('AdminLogNoticeController@getReadAll') }}"><i class="fa fa-telegram"></i> Mark All Notice as Read</a>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-body">
		<div class="table-responsive">
			<table id="example" class="table table-bordered display" style="width:100%">
				<thead>
					<tr>
						<th class="no-sort" style="width: 10px;"><input type="checkbox" id="checkAll"></th>
						<th>Customer</th>
						<th>Content</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($row as $list)
					<tr>
						<td><label class="labeling"><input type="checkbox" class="radios list_id" value="{{ $list->id }}" name="list_id"></label></td>
						<td>{{ $list->customers_name }}</td>
						<td>{{ $list->content }}</td>
						<td>
							<a class='btn btn-xs btn-primary btn-detail' title='Detail Data'
							href='{{ action("AdminLogNoticeController@getDetail") }}/{{ $list->id }}'><i class='fa fa-eye'></i></a>
							<a class='btn btn-xs btn-success btn-edit' title='Edit Data'
							href='{{ CB()->getAdminUrl() }}/log_notice/edit/{{ $list->id }}'><i
							class='fa fa-pencil'></i></a>
							<a class='btn btn-xs btn-danger btn-delete' title='Delete Data' onclick="deleteConfirmation('{{ CB()->getAdminUrl() }}/log_notice/delete/{{ $list->id }}')" href='javascript:;'
								><i class='fa fa-trash'></i></a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endsection
	@push('bottom')
	<script type="text/javascript">
		$('#example').DataTable({
			dom: 'lftipr',
            "columnDefs": [ {
                "targets": 'no-sort',
                "orderable": false,
            } ]
		});
	</script>
	<script type="text/javascript">
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
		$(document).ready(function () {
			$('.btn-bulk').on('click', function(){
				var row_id = [];
				$("input[name='list_id']:checked").each(function(){
					row_id.push(this.value);
				});

				if (row_id.length < 1) {
					swal({
						icon: "warning",
						text: "Pilih Beberapa Orderan Terlebih Dahulu!",
					});
				}else{
					$('.loading').show();
					$.ajax({
						url: '{{ action("AdminLogNoticeController@postRead") }}',
						type: 'POST',
						data: {id:row_id},
						dataType: 'JSON',
						success: function(response){
							swal({
								icon: "success",
								text: "Berhasil!",
							});
							location.reload();
						},
						error: function(response) {
							console.log(response);
						},
					});
				}
			});
		});
	</script>
	@endpush