@extends('crudbooster::layouts.layout')
@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<h1 class="box-title"><i class="fa fa-eye"></i> Detail</h1>
	</div>
	<div class="box-body">
		<div class='table-responsive'>
			<table id='table-detail' class='table table-striped'>
				<tr>
					<th width="25%">Photo</th>
					<td>
						@if($row->getPhoto() != NULL)
						<div align="left">
							<a data-lightbox="preview-image" title="Preview Image" href="{{ asset($row->getPhoto()) }}">
								<img src="{{ asset($row->getPhoto()) }}" style="height: 150px" alt="Photo">
							</a>
						</div>
						@else
						<span class="label label-danger label-xs" style="text-transform: capitalize">Photo Not Found!</span>
						@endif
					</td>
				</tr>
				<tr>
					<th width="25%">Nama Menu</th>
					<td>{{ $row->getName() }}</td>
				</tr>
				<tr>
					<th width="25%">Type Produk</th>
					<td>{{ $row->getProductId() }}</td>
				</tr>
				<tr>
					<th width="25%">Disediakan Tanggal</th>
					<td>{{ Carbon\Carbon::parse($row->getMenuDate())->format('d F Y') }}</td>
				</tr>
				<tr>
					<th width="25%">Allergen</th>
					<td>
						<?php
						$alergen = json_decode($row->getAlergy());
						if($alergen){
							foreach ($alergen as $key => $alergy) {
								echo $alergy->alergy.', ';
							}
						}else{
							echo '-';
						}
						?>
					</td>
				</tr>
				<tr>
					<th width="25%">Kandungan protein dari</th>
					<td>{{ ($row->getProteinFrom() == NULL ? '-' : $row->getProteinFrom()) }}</td>
				</tr>
				<tr>
					<th width="25%">Kandungan Karbo dari</th>
					<td>{{ ($row->getCarboFrom() == NULL ? '-' : $row->getCarboFrom()) }}</td>
				</tr>
				<tr>
					<th width="25%">Estimasi HPP</th>
					<td>{{ ($row->getPriceHpp() == NULL ? '-' : 'Rp. '.number_format($row->getPriceHpp())) }}</td>
				</tr>
				<tr>
					<th width="25%">Estimasi HPP Propack</th>
					<td>{{ ($row->getPriceHppP() == NULL ? '-' : 'Rp. '.number_format($row->getPriceHppP())) }}</td>
				</tr>
				<tr>
					<th width="25%">Nutrition Facts</th>
					<td>
						<div class="row">
							<div class="col-md-6">
								<h4>Regular</h4>
								<table class="table table-bordered">
									<tr>
										<th>Protein:</th>
										<td>{{ ($row->getProtein() == NULL ? 0 : $row->getProtein()) }} gr</td>
										<th>Gula:</th>
										<td>{{ ($row->getGula() == NULL ? 0 : $row->getGula()) }} gr</td>
									</tr>
									<tr>
										<th>Carbs:</th>
										<td>{{ ($row->getCarbo() == NULL ? 0 : $row->getCarbo()) }} gr</td>
										<th>Saturated Fat:</th>
										<td>{{ ($row->getSaturatedFat() == NULL ? 0 : $row->getSaturatedFat()) }} gr</td>
									</tr>
									<tr>
										<th>Lemak:</th>
										<td>{{ ($row->getFat() == NULL ? 0 : $row->getFat()) }} gr</td>
										<th>Kalori:</th>
										<td>{{ ($row->getCalory() == NULL ? 0 : $row->getCalory()) }} KKal</td>
									</tr>
								</table>
							</div>
							<div class="col-md-6">
								<h4>Propack</h4>
								<table class="table table-bordered">
									<tr>
										<th>Protein:</th>
										<td>{{ ($row->getProteinP() == NULL ? 0 : $row->getProteinP()) }} gr</td>
										<th>Gula:</th>
										<td>{{ ($row->getGulaP() == NULL ? 0 : $row->getGulaP()) }} gr</td>
									</tr>
									<tr>
										<th>Carbs:</th>
										<td>{{ ($row->getCarboP() == NULL ? 0 : $row->getCarboP()) }} gr</td>
										<th>Saturated Fat:</th>
										<td>{{ ($row->getSaturatedFatP() == NULL ? 0 : $row->getSaturatedFatP()) }} gr</td>
									</tr>
									<tr>
										<th>Lemak:</th>
										<td>{{ ($row->getFatP() == NULL ? 0 : $row->getFatP()) }} gr</td>
										<th>Kalori:</th>
										<td>{{ ($row->getCaloryP() == NULL ? 0 : $row->getCaloryP()) }} KKal</td>
									</tr>
								</table>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
@endsection