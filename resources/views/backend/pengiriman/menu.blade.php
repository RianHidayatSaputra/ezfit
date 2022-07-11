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
	<?php $count = 0; ?>
	@foreach($menu as $m)
	@foreach($m->package_list as $arr)
	<?php $count += 1; ?>
	<div class="container">
		<table style="border-collapse: collapse; width: 198.33px;">
			<tr>
				<td colspan="2" style="height: 50px; font-size: 12px;"><b><center>{{ $m->name }} {{($arr['type'] == 'Propack' ? ' (PP)' : '')}}</center></b></td>
			</tr>
			<tr>
				<td>NUTRITION FACTS</td>
				<td>Amount/Serving</td>
			</tr>
			<tr>
				<td>Fat</td>
				<td><center>{{($arr['type'] == 'Propack' ? $m->fat_p : $m->fat)}} g</center></td>
			</tr>
			<tr>
				<td>Protein</td>
				<td><center>{{($arr['type'] == 'Propack' ? $m->protein_p : $m->protein)}} g</center></td>
			</tr>
			<tr>
				<td>Cabohydrate</td>
				<td><center>{{($arr['type'] == 'Propack' ? $m->carbo_p : $m->carbo)}} g</center></td>
			</tr>
			<tr>
				<td><span style="font-weight: bold;font-size: 14px;">Calories</span></td>
				<td><center><span style="font-weight: bold;font-size: 14px;">{{($arr['type'] == 'Propack' ? round($m->calory_p,2) : round($m->calory,2))}}</span></center></td>
			</tr>
			<tr>
				<td colspan="2">
					Calories per gram :<br>
					<center>Fat : 9 - Carbohydrate : 4 - Protein : 4</center>
				</td>
			</tr>
		</table>
	</div>
	@if($count%15 == 0)
	<div style="page-break-after: always;"></div>
	@endif
	@endforeach
	@endforeach
	<script type="text/javascript">
		window.print();
	</script>
</body>
</html>