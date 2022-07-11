@extends('crudbooster::layouts.layout')
@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header">
				<h1 class="box-title with-border">Regular</h1>
			</div>
			<div class="box-body">
				<table class="table table-striped table-boredered">
					<thead>
						<tr>
							<th>#</th>
							<th>Periode</th>
							<th>Percen (%)</th>
							<th>Type Package</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($regular as $key => $row)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ $row->periode }}</td>
							<td>{{ ($row->percen == NULL ? '0' : $row->percen) }}</td>
							<td>{{ $row->type_package }}</td>
							<td><a href="{{ action('AdminMasterPackageController@getEdit') }}/{{ $row->id }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header">
				<h1 class="box-title with-border">Propack</h1>
			</div>
			<div class="box-body">
				<table class="table table-striped table-boredered">
					<thead>
						<tr>
							<th>#</th>
							<th>Periode</th>
							<th>Percen (%)</th>
							<th>Type Package</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($propack as $key => $row)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ $row->periode }}</td>
							<td>{{ ($row->percen == NULL ? '0' : $row->percen) }}
							</td>
							<td>{{ $row->type_package }}</td>
							<td><a href="{{ action('AdminMasterPackageController@getEdit') }}/{{ $row->id }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection