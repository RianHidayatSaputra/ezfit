@extends('crudbooster::layouts.layout')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap.min.css">
@if(g('date_range'))
<div class="box box-default">
	<div class="box-body table-responsive no-padding">
		<table class='table table-bordered'>
			<tbody>
				<tr class='active'>
					<td>
						<h5>
							<strong><i class='fa fa-bars'></i> Detail Filter</strong>
						</h5>
					</td>
					<td><a class="btn btn-success btn-sm" href="{{ action('AdminPenjualanController@getIndex') }}"><i class='fa fa-bars'></i> Reset Filter</a></td>
				</tr>
				<tr>
					<td width="25%">
						<strong>
							Date Range
						</strong>
					</td>
					<td>
						{{ $date_start }} s/d {{ $date_end }}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@endif
<div class="box">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
					<table class="table table-bordered" id="table-action">
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal</th>
								<th>ID Order</th>
								<th>Nama</th>
								<th>No. Telp</th>
								<th>Tanggal Mulai</th>
								<th>Periode</th>
								<th>Type Package</th>
								<th>Package</th>
								<th>Day Off</th>
								<th>Days Left</th>
								<th>Alergen</th>
								<th>Special Request</th>
								<th>Delivery Address</th>
								<th>Type Customer</th>
								<th>Sub Total</th>
								<th>Discount</th>
								<th>Kode Voucher</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data as $key => $row)
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $row->date }}</td>
								<td>{{ $row->order_id }}</td>
								<td>{{ $row->name }}</td>
								<td>{{ $row->telp }}</td>
								<td>{{ $row->start_date }}</td>
								<td>{{ $row->periode }}</td>
								<td>{{ $row->type_package }}</td>
								<td>{{ $row->package }}</td>
								<td>{{ $row->day_off }}</td>
								<td>{{ $row->days_left }}</td>
								<td>{{ $row->alergen }}</td>
								<td>{{ ($row->special_req == '' ? '-' : $row->special_req) }}</td>
								<td>{{ $row->address }}</td>
								<td>{{ ucwords($row->type_customer) }}</td>
								<td>Rp.{{ number_format($row->sub_total) }}</td>
								<td>Rp.{{ number_format($row->discount) }}</td>
								<td>{{ ($row->vouchers_code == NULL ? '-' : $row->vouchers_code) }}</td>
								<td>Rp.{{ number_format($row->total) }}</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>No</th>
								<th>Tanggal</th>
								<th>ID Order</th>
								<th>Nama</th>
								<th>No. Telp</th>
								<th>Tanggal Mulai</th>
								<th>Periode</th>
								<th>Type Package</th>
								<th>Package</th>
								<th>Day Off</th>
								<th>Days Left</th>
								<th>Alergen</th>
								<th>Special Request</th>
								<th>Delivery Address</th>
								<th>Type Customer</th>
								<th>Sub Total</th>
								<th>Discount</th>
								<th>Kode Voucher</th>
								<th>Total</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('bottom')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#table-action').DataTable( {
			dom: 'Blfrtip',
			lengthChange: false,
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
		});

		table.buttons().container()
		.appendTo( '#table-action-wrapper .col-sm-6:eq(0)' );
	});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('input[name="date_range"]').daterangepicker({
			showDropdowns: true,
			minYear: 2019,
			ranges: {
				'Hari Ini': [moment(), moment()],
				'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Minggu Ini': [moment().startOf('week'), moment().endOf('week')],
				'Minggu Lalu': [moment().subtract(6, 'days'), moment()],
				'2 Minggu Terakhir': [moment().subtract(13, 'days'), moment()],
				'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
				'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'),
				moment().subtract(1, 'month').endOf('month')]
			},
			autoUpdateInput: true,
			applyClass: 'btn-sm btn-success',
			cancelClass: 'btn-sm btn-danger',
			locale: {
				format: 'YYYY-MMMM-DD',
				applyLabel: 'Submit',
				cancelLabel: 'Cancel',
				fromLabel: 'Dari',
				toLabel: 'Hingga',
				customRangeLabel: 'Custom Date',
				daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
				monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
				'Juli', 'Agustus', 'September', 'Oktober', 'November',
				'Desember'],
				firstDay: 1
			}
		});
	});
</script>
@endpush