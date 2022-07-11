<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;
use App\Models\TrxOrders;
use App\Models\TrxOrdersAlergy;
use App\Models\TrxOrdersStatus;

use Carbon\Carbon;

class AdminPenjualanController extends CBController {


	public function cbInit()
	{
		$this->setTable("trx_orders");
		$this->setPermalink("penjualan");
		$this->setPageTitle("Penjualan");
	}

	public function getIndex(){
		$data['page_title'] = "Report Penjualan";
		$row = TrxOrders::simpleQuery()
		->leftjoin('packages','packages.id','=','trx_orders.packages_id')
		->leftjoin('customers','customers.id','=','trx_orders.customers_id')
		->where('status_payment','Success Payment')
		->whereNull('is_paused');

		if (g('date_range')) {
			$date_range = explode(' - ', g('date_range'));
			$date_start = Carbon::parse($date_range[0])->format('Y-m-d');
			$date_end = Carbon::parse($date_range[1])->format('Y-m-d');

			$row = $row
			->whereBetween('must_end',[$date_start,$date_end])
			->whereBetween('tgl_mulai',[$date_start,$date_end]);

			$data['date_start'] = Carbon::parse($date_range[0])->format('d F Y');
			$data['date_end'] = Carbon::parse($date_range[1])->format('d F Y');
		}else{
			$date_start = Carbon::now()->firstOfMonth()->format('Y-m-d');
			$date_end = Carbon::now()->endOfMonth()->format('Y-m-d');

			$data['date_start'] = Carbon::now()->firstOfMonth()->format('d F Y');
			$data['date_end'] = Carbon::now()->endOfMonth()->format('d F Y');
		}

		$row = $row
		->select('trx_orders.id','trx_orders.created_at as date','trx_orders.no_order as order_id','customers.name','customers.ho_hp as telp','trx_orders.tgl_mulai as start_date','trx_orders.periode','packages.type_package','packages.name as package','trx_orders.day_off','trx_orders.id as days_left','trx_orders.id as alergen','trx_orders.id as special_req','trx_orders.protein_alternative','trx_orders.carbo_alternative','trx_orders.address','customers.type_customer','trx_orders.price as sub_total','trx_orders.id as discount','trx_orders.vouchers_code','trx_orders.total')
		->get();

		foreach ($row as $key => $val) {
			$val->date = Carbon::parse($val->date)->format('d F Y H:i');
			$val->start_date = Carbon::parse($val->start_date)->format('d F Y');

			$day_off = json_decode($val->day_off);
			$day_off_result = '';
			if (count((array)$day_off) != 0) {
				foreach ($day_off as $key => $do) {
					$day_off_result .= ucwords($do->day_off).','; 
				}
			}else{
				$day_off_result = '-';
			}
			$val->day_off = $day_off_result;

			$history = TrxOrdersStatus::simpleQuery()
			->where('trx_orders_id',$val->id)
			->orderBy('date','desc')
			->groupBy('date','trx_orders_id')
			->select('date','trx_orders_id')
			->get();

			$days_left_total = 0;
			foreach ($history as $y){
				$days_left_total += 1;
			}

			$val->days_left = $val->periode - $days_left_total;

			$alergen = TrxOrdersAlergy::simpleQuery()
			->leftjoin('master_alergy','trx_orders_alergy.master_alergy_id','=','master_alergy.id')
			->where('trx_orders_id',$val->id)
			->select('master_alergy.name')
			->get();

			$alergen_result = '';
			if ($alergen->count() != 0) {
				foreach ($alergen as $key => $a) {
					$alergen_result .= $a->name.', ';
				}
			}else{
				$alergen_result = '-';
			}

			if ($val->protein_alternative == NULL || $val->carbo_alternative ==  NULL) {
				$paginator = '';
			}else{
				$paginator = ' - ';
			}
			$val->special_req = $val->protein_alternative.$paginator.$val->carbo_alternative;
			$val->alergen = $alergen_result;
			$val->discount = $val->sub_total - $val->total;
		}

		$data['data'] = $row;
		return view('backend.report.penjualan',$data);

	}
}
