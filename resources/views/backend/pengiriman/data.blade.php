<?php
header("Pragma: public");
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="REPORT DATA PACKING | '.date('d F Y').'.xls"');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{{ $page_title }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta name='generator' content='CRUDBooster'/>
	<meta name='robots' content='noindex,nofollow'/>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<style type="text/css">
		table {border-collapse: collapse;}
		th {background-color: #ebebeb;}
		th, td {border: 1px solid #000;padding:5px;}
	</style>
</head>
<body>
	
	<table>
		<tr>
			<th>No</th>
			<th>ID Order</th>
			<th>Type Package</th>
			<th>Package</th>
			<th>Nama Customer</th>
			<th>No HP</th>
			<th>Alergen</th>
			<th>Request Khusus</th>
			<th>Alamat + Detail Alamat + Alternatif Penerima</th>
			<th>Driver</th>
		</tr>
		@foreach($row as $key => $r)
		<tr>
			<td>{{ $key + 1}}</td>
			<td>{{ $r->no_order }}</td>
			<td>{{ $r->type_package }}</td>
			<td>{{ $r->packages }}</td>
			<td>{{ $r->customer }}</td>
			<td>{{ $r->telp}}</td>
			<td>
				@foreach($r->alergy as $a)
				{{ $a['name'] }}, 
				@endforeach
			</td>
			<td>{{ $r->protein }} - {{ $r->carbo }}</td>
			<td>{{ $r->address }} - {{ $r->detail_address }}, {{ $r->catatan }}</td>
			<td>{{ $r->driver }}</td>
		</tr>
		@endforeach
	</table>
</body>
</html>