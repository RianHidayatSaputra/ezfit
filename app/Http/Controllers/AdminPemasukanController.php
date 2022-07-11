<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;
use App\Models\TrxOrders;
use App\Models\Packages;
use App\Repositories\PackagesRepository;
use Carbon\Carbon;

class AdminPemasukanController extends CBController {


    public function cbInit()
    {
        $this->setTable("trx_orders");
        $this->setPermalink("pemasukan");
        $this->setPageTitle("Pemasukan");
    }

    public function getIndex(){
        $data['page_title'] = 'Report Pemasukan';
        $packages = TrxOrders::simpleQuery()
        ->leftjoin('customers','trx_orders.customers_id','=','customers.id')
        ->where('status_payment','Success Payment')
        ->whereNull('is_paused')
        ->orderBy('trx_orders.packages_id','asc');

        if (g('date_range') != NULL || g('date_range') != '') {
            $date_range = explode(' - ', g('date_range'));
            $date_start = Carbon::parse($date_range[0])->format('Y-m-d');
            $date_end = Carbon::parse($date_range[1])->format('Y-m-d');

            $packages = $packages
            ->whereBetween('trx_orders.created_at', [$date_start, $date_end]);

            $data['date_start'] = Carbon::parse($date_range[0])->format('d F Y');
            $data['date_end'] = Carbon::parse($date_range[1])->format('d F Y');
        }else{
            $date_start = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $date_end = Carbon::now()->endOfMonth()->format('Y-m-d');
            $packages = $packages
            ->whereBetween('trx_orders.created_at', [$date_start, $date_end]);
        }

        if (g('periode') != NULL || g('periode') != '') {
            $packages = $packages
            ->where('trx_orders.periode',g('periode'));
        }

        if (g('type_customer') != NULL || g('periode') != '') {
            $packages = $packages
            ->where('customers.type_customer',g('type_customer'));
        }
        
        $packages = $packages->select('packages_id','periode','customers.type_customer as type')
        ->groupBy('packages_id','periode','type')
        ->get();

        $arr = [];
        foreach ($packages as $key => $val) {
            $package = Packages::simpleQuery()
            ->where('id',$val->packages_id)
            ->first();

            $price = PackagesRepository::getPrice($val->packages_id,$val->periode,$val->type);

            $qty = TrxOrders::simpleQuery()
            ->leftjoin('customers','trx_orders.customers_id','=','customers.id')
            ->where('status_payment','Success Payment')
            ->whereNull('is_paused')
            ->where('packages_id',$val->packages_id)
            ->where('periode',$val->periode)
            ->where('customers.type_customer',$val->type)
            ->count();

            $arr[] = array(
                'type_package' => $package->type_package,
                'package' => $package->name,
                'periode' => $val->periode,
                'type_customer' => $val->type,
                'price' => $price['real_price'],
                'qty' => $qty,
                'total' => $price['real_price'] * $qty,
            );
        }

        $data['data'] = $arr;
        return view('backend.report.pemasukan',$data);
    }
}
