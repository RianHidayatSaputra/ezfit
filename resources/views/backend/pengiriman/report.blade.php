<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{{ $page_title }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta name='generator' content='CRUDBooster'/>
	<meta name='robots' content='noindex,nofollow'/>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<!-- Bootstrap 3.3.2 -->
	<style type="text/css">
		@page { size: auto; margin: 15mm 0 54mm 50; } body { margin:0; padding:0;}
		.container{
			float: left;
			margin: 10px;
		}
	
		tr,td{
			color: #333;
			font-size: 10px;
			font-family: tahoma;
			border: 1px solid #333;
			padding-left: 10px;
			padding: 2px 2px;
		}
	</style>
</head>
<body>
	<?php
		$num = 0;
	?>
	@foreach($row as $key => $r)
	<?php
		$num += 1;
	?>
	<div class="container">
		<table style="border-collapse: collapse; width: 215px;height: 350px;">
			<tr>
				<td style="width: 70px;">Nama</td>
				<td>{{ $r['nama'] }}</td>
			</tr>
			<tr>
				<td>Package</td>
				<td style="height: 20px;">{{ ($r['package'] == NULL ? '-' : $r['package']) }}</td>
			</tr>
			<tr>
				<td>Request Khusus</td>
				<td  style="height: 20px;">
					@if(strpos($r['package'], 'Lunch') !== FALSE)
					L : {{ $r['request_khusus_p_l'] }} - {{ $r['request_khusus_c_l'] }}<br>
					@endif
					@if(strpos($r['package'], 'Dinner') !== FALSE)
					D : {{ $r['request_khusus_p_d'] }} - {{ $r['request_khusus_c_d'] }}
					@endif
				</td>
			</tr>
			<tr>
				<td>Alergen</td>
				<td style="height: 20px;">
					@if(strpos($r['package'], 'Lunch') !== FALSE)
					L : {{ $r['alergen_l'] }}<br>
					@endif
					@if(strpos($r['package'], 'Dinner') !== FALSE)
					D : {{ $r['alergen_d'] }}
					@endif
				</td>
			</tr>
			<tr>
				<td>No. HP</td>
				<td>{{ $r['no_hp'] }}</td>
			</tr>
			<tr>
				<td>Kurir / HP</td>
				<td>{{ $r['kurir_hp'] }}</td>
			</tr>
			<tr>
				<td>Alamat</td>
				<td style="height: 40px;">{{ $r['address'] }}</td>
			</tr>
			<tr>
				<td>Detail Alamat</td>
				<td style="height: 32px;">{{ $r['detail_address'] }}</td>
			</tr>
			<tr>
				<td>Catatan</td>
				<td style="height: 30px;">{{ $r['catatan'] }}</td>
			</tr>
		</table>
	</div>
	@if($num%9 == 0)
	<div style="page-break-after: always;"></div>
	@endif
	@endforeach
	<script type="text/javascript">
		window.print();
	</script>
</body>
</html>