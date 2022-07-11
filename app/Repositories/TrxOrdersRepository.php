<?php
namespace App\Repositories;

use App\Models\TrxOrdersDate;
use App\Models\LogNotice;
use App\Models\TrxOrdersAlergy;
use DB;
use App\Models\TrxOrders;
use App\Models\TrxOrdersStatus;
use App\Repositories\VouchersRepository;
use Carbon\Carbon;
use App\Models\Menus;
use App\Models\Drivers;

class TrxOrdersRepository extends TrxOrders
{
    public static function Transaksi($id){
        $order = DB::table('trx_orders')
        ->select('trx_orders.*','')
        ->join('customers','customers.id','trx_orders.customers_id')
        ->join('packages','packages.id','trx_orders.packages_id')
        ->join('drivers','drivers.id','trx_orders.drivers_id')
        ->where('id',$id)
        ->first();

        return new static($order);
    }
    public static function getPengiriman(){
        $order = static::simpleQuery()
        ->get();

    }
    public static function deleteRelation($id){
        TrxOrdersStatus::simpleQuery()->where('trx_orders_id',$id)->delete();
        LogNotice::simpleQuery()->where('trx_orders_id',$id)->delete();

        static::simpleQuery()->where('id',$id)->delete();

        $dtl = TrxOrdersAlergy::simpleQuery()->where('trx_orders_id',$id)->delete();
    }

    public static function requestOrders(){
        $query = static::simpleQuery()->whereIn('trx_orders.status_payment',['Waiting Payment','Confirmation'])
        ->join('customers','trx_orders.customers_id','=','customers.id')
        ->join('packages','trx_orders.packages_id','=','packages.id')
        ->select('trx_orders.id','customers.name AS name','trx_orders.no_order','trx_orders.tgl_mulai','packages.name AS packages','trx_orders.total','trx_orders.status_payment')
        ->orderBy('id','asc')
        ->get();

        foreach ($query as $key => $q) {
            $q->tgl_mulai = Carbon::parse($q->tgl_mulai)->format('d-M-Y');
            $order = TrxOrders::findById($q->id);
            if ($order->getStatusPayment() != 'Failed'){
                $riwayat = TrxOrdersStatus::simpleQuery()
                ->where('trx_orders_id',$q->id)
                ->orderBy('date','desc')
                ->groupBy('date','trx_orders_id')
                ->select('date','trx_orders_id')
                ->get();
                $ttl = 0;
                foreach ($riwayat as $y){
                    $ttl += 1;
                }
                $d = $order->getPeriode() - $ttl;
                if ($order->getisPaused() == 1){
                    $q->days_left = 'Paused';
                }else {
                    if ($d <= 0) {
                        $q->days_left = 'Expired';
                    }else{
                        $q->days_left = $d;
                    }
                }
            }else{
                $q->days_left = 'Cancel';
            }
        }

        return $query;
    }

    public static function OrdersSuccessToday(){
        $query = static::simpleQuery()
        ->where('status_payment','Success Payment')
        ->whereDate('updated_at',date('Y-m-d'))
        ->get();

        return $query;
    }

    public static function deliveryToday(){
        if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
            $date = getMustEnd(date('Y-m-d'),1);
        }else{
            $date = date('Y-m-d');
        }
        $list = TrxOrdersDate::simpleQuery()
        ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
        ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
        ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
        ->join('customers','customers.id','=','trx_orders.customers_id')
        ->where('trx_orders_date.date',$date)
        ->where('status_payment','Success Payment')
        ->whereNull('is_paused')
        ->select('trx_orders.*','drivers.name as driver_name','drivers.no_wa as driver_no','packages.name as package_name','customers.name as c_name','customers.ho_hp as c_ho_hp')
        ->get();

        $d = HariApa($date);
        if ($d == 'Minggu'){
            $date = getMustEnd($date,1);
        }else{
            $date = $date;
        }
        
        $d = HariApa($date);
        $d = strtolower($d);

        $total_today = 0;
        foreach ($list as $key => $aria) {
            $off = json_decode($aria->day_off);
            $off_d = [];
            if ($date <= $aria->must_end){
                if ($off){
                    foreach ($off as $y){
                        $off_d[] = $y->day_off;
                    }
                }else{
                    $off_d = [];
                }
                if (!in_array($d,$off_d)){
                    $total_today += 1;
                }
            }
        }

        return $total_today;
    }

    public static function getDiscount($id){
        $query = TrxOrders::findById($id);
        $price = $query->getPrice();
        $total = $query->getTotal();

        $result = $total - $price;
        return $result;
    }

    public static function getTotalBox($type,$package,$date){
        $d = HariApa($date);
        $d = strtolower($d);

        $query = TrxOrdersDate::simpleQuery()
            ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
            ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
            ->where('trx_orders_date.date',$date)
            ->where('status_payment','Success Payment')
            ->whereNull('is_paused')
            ->where('packages.type_package',$type)
            ->where('packages.category','like','%'.$package.'%')
            ->get();

        $total_box = 0;
        foreach ($query as $key => $q) {
            $off = json_decode($q->day_off);
            $off_d = [];
            if ($date <= $q->must_end){
                if ($off){
                    foreach ($off as $y){
                        $off_d[] = $y->day_off;
                    }
                }else{
                    $off_d = [];
                }
                if (!in_array($d,$off_d)){
                    $total_box += 1;
                }
            }
        }

        return $total_box;
    }
    public static function listMenuDapur($type_package,$date){
        $d = HariApa($date);
        $d = strtolower($d);

        $list_menu = Menus::simpleQuery()
            ->where('menu_date',$date)
            ->whereNotIn('product_id',['Salad','Snack'])
            ->get();

        $arr = [];
        foreach ($list_menu as $row){
            $alrg = TrxOrdersDate::simpleQuery()
                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('trx_orders_date.date',$date)
                ->where('status_payment','Success Payment')
                ->where('packages.type_package',$type_package)
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->whereNull('is_paused')
                ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                ->get();
            $jumlah_alerg = [];
            $array_trx = [];
            foreach ($alrg as $al){
                $off = json_decode($al->day_off);
                $off_d = [];
                if ($date <= $al->must_end){
                    if ($off){
                        foreach ($off as $y){
                            $off_d[] = $y->day_off;
                        }
                    }else{
                        $off_d = [];
                    }
                    if (!in_array($d,$off_d)){
                        $check_in_ta = DB::table('trx_orders_alergy')
                            ->leftjoin('master_alergy','trx_orders_alergy.master_alergy_id','=','master_alergy.id')
                            ->where('trx_orders_id',$al->id)
                            ->select('master_alergy.name','trx_orders_alergy.trx_orders_id as trx_id')
                            ->get();
                        if ($check_in_ta) {
                            foreach ($check_in_ta as $key => $cit) {
                                if(strpos($row->alergy,$cit->name) !== false) {
                                    $jumlah_alerg[] = array('customer' => $al->customer_name);
                                    $array_trx[] = array($cit->trx_id);
                                }
                            }
                        }
                    }
                }
            }

            // Protein
            $pro = [];
            $protein = TrxOrdersDate::simpleQuery()
                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->whereNotIn('trx_orders.id',$array_trx)
                ->where('trx_orders_date.date',$date)
                ->where('status_payment','Success Payment')
                ->whereNull('is_paused')
                ->where('packages.type_package',$type_package)
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->where('protein_alternative','!=',$row->protein_from)
                ->where('protein_alternative')
                ->orWhere('protein_alternative',NULL)
                ->select('protein_alternative')
                ->groupBy('protein_alternative')
                ->get();

            foreach ($protein as $key => $p){
                $list = TrxOrdersDate::simpleQuery()
                    ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->whereNotIn('trx_orders.id',$array_trx)
                    ->where('trx_orders_date.date',$date)
                    ->where('status_payment','Success Payment')
                    ->where('packages.type_package',$type_package)
                    ->where('packages.category','like','%'.$row->product_id.'%')
                    ->where('protein_alternative',$p->protein_alternative)
                    ->whereNull('is_paused')
                    ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                    ->get();

                $list_pr = TrxOrdersDate::simpleQuery()
                    ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->whereNotIn('trx_orders.id',$array_trx)
                    ->where('trx_orders_date.date',$date)
                    ->where('status_payment','Success Payment')
                    ->where('packages.type_package',$type_package)
                    ->where('packages.category','like','%'.$row->product_id.'%')
                    ->where('protein_alternative',$row->protein_from)
                    ->whereNull('is_paused')
                    ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                    ->get();

                $ttl = 0;
                $ttl_2 = 0;
                $user = [];
                $user_p = [];
                foreach ($list_pr as $key => $vle) {
                    $off = json_decode($vle->day_off);
                    $off_d = [];
                    if ($date <= $vle->must_end){
                        if ($off){
                            foreach ($off as $y){
                                $off_d[] = $y->day_off;
                            }
                        }else{
                            $off_d = [];
                        }
                        if (!in_array($d,$off_d)){
                            $no_protein = TrxOrdersDate::simpleQuery()
                                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                                ->where('trx_orders_date.date',$date)
                                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                                ->where('status_payment','Success Payment')
                                ->whereNull('is_paused')
                                ->where('packages.type_package',$type_package)
                                ->where('packages.category','like','%'.$row->product_id.'%')
                                ->where('protein','like','%'.$row->protein_from.'%')
                                ->orWhere('protein',NULL)
                                ->select('protein')
                                ->groupBy('protein')
                                ->get();

                            if ($no_protein) {
                                foreach ($no_protein as $key => $no) {
                                    if($no->protein == TRUE) {
                                        $ttl_2 += 1;
                                        $user_p[] = array(
                                            'customer' => $vle->customer_name,
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
                foreach ($list as $r){
                    $off = json_decode($r->day_off);
                    $off_d = [];
                    if ($date <= $r->must_end){
                        if ($off){
                            foreach ($off as $y){
                                $off_d[] = $y->day_off;
                            }
                        }else{
                            $off_d = [];
                        }
                        if (!in_array($d,$off_d)){

                            $ttl += 1;
                            $user[] = array(
                                'customer' => $r->customer_name
                            );

                        }
                    }
                }


                if ($p->protein_alternative == NULL ) {
                    $p->protein_alternative = $row->protein_from;
                    $ttl = $ttl+$ttl_2;
                    $user = [];
                }

                $pro[] = array(
                    'protein'=>$p->protein_alternative,
                    'total' => $ttl,
                    'customer'=> $user,
                );
            }

            //alter Protein
            $hinpro = [];
            $hinprotein = TrxOrdersDate::simpleQuery()
                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                ->where('trx_orders_date.date',$date)
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->where('status_payment','Success Payment')
                ->whereNull('is_paused')
                ->where('packages.type_package',$type_package)
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->where('protein_alternative','!=',$row->protein_from)
                // ->orWhere('protein_alternative',NULL)
                ->select('protein_alternative')
                ->groupBy('protein_alternative')
                ->get();

            foreach ($hinprotein as $key => $p){
                $list = TrxOrdersDate::simpleQuery()
                    ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->where('trx_orders_date.date',$date)
                    ->where('status_payment','Success Payment')
                    ->where('packages.type_package',$type_package)
                    ->where('packages.category','like','%'.$row->product_id.'%')
                    ->where('protein_alternative',$p->protein_alternative)
                    ->where('protein','like','%'.$row->protein_from.'%')
                    ->whereNull('is_paused')
                    ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                    ->get();

                $list_cr = TrxOrdersDate::simpleQuery()
                    ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->where('trx_orders_date.date',$date)
                    ->where('status_payment','Success Payment')
                    ->where('packages.type_package',$type_package)
                    ->where('packages.category','like','%'.$row->product_id.'%')
                    ->where('protein_alternative',$row->protein_from)
                    ->whereNull('is_paused')
                    ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                    ->get();

                $ttl = 0;
                $ttl_2 = 0;
                $user = [];
                $user_p = [];
                foreach ($list_cr as $key => $vle) {
                    $off = json_decode($vle->day_off);
                    $off_d = [];
                    if ($date <= $vle->must_end){
                        if ($off){
                            foreach ($off as $y){
                                $off_d[] = $y->day_off;
                            }
                        }else{
                            $off_d = [];
                        }
                        if (!in_array($d,$off_d)){
                            $hinno_protein = TrxOrdersDate::simpleQuery()
                                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                                ->where('trx_orders_date.date',$date)
                                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                                ->where('status_payment','Success Payment')
                                ->whereNull('is_paused')
                                ->where('packages.type_package',$type_package)
                                ->where('packages.category','like','%'.$row->product_id.'%')
                                ->where('protein','like','%'.$row->protein_from.'%')
                                ->orWhere('protein',NULL)
                                ->select('protein')
                                ->groupBy('protein')
                                ->get();
                            if ($hinno_protein) {
                                foreach ($hinno_protein as $key => $no) {
                                    if($no->protein == TRUE) {
                                        $ttl_2 += 1;
                                        $user_p[] = array(
                                            'customer' => $vle->customer_name
                                        );
                                    }
                                }
                            }
                        }
                    }
                }

                foreach ($list as $r){
                    $off = json_decode($r->day_off);
                    $off_d = [];
                    if ($date <= $r->must_end){
                        if ($off){
                            foreach ($off as $y){
                                $off_d[] = $y->day_off;
                            }
                        }else{
                            $off_d = [];
                        }
                        if (!in_array($d,$off_d)){
                            $ttl += 1;
                            $user[] = array(
                                'customer' => $r->customer_name,
                            );
                        }
                    }
                }

                if ($p->protein_alternative == NULL ) {
                    $p->protein_alternative = $row->protein_from;
                    $ttl = $ttl+$ttl_2;
                    $user = [];
                }

                $hinpro[] = array(
                    'hinprotein'=>$p->protein_alternative,
                    'total' => $ttl,
                    'customer'=> $user,
                );
            }

            // carbo
            $car = [];
            $carbo = TrxOrdersDate::simpleQuery()
                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->whereNotIn('trx_orders.id',$array_trx)
                ->where('trx_orders_date.date',$date)
                ->where('status_payment','Success Payment')
                ->whereNull('is_paused')
                ->where('packages.type_package',$type_package)
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->where('carbo_alternative','!=',$row->carbo_from)
                ->where('carbo_alternative')
                ->orWhere('carbo_alternative',NULL)
                ->select('carbo_alternative')
                ->groupBy('carbo_alternative')
                ->get();

            foreach ($carbo as $key => $c){
                $list = TrxOrdersDate::simpleQuery()
                    ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->whereNotIn('trx_orders.id',$array_trx)
                    ->where('trx_orders_date.date',$date)
                    ->where('status_payment','Success Payment')
                    ->where('packages.type_package',$type_package)
                    ->where('packages.category','like','%'.$row->product_id.'%')
                    ->where('carbo_alternative',$c->carbo_alternative)
                    ->whereNull('is_paused')
                    ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                    ->get();

                $list_cr = TrxOrdersDate::simpleQuery()
                    ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->whereNotIn('trx_orders.id',$array_trx)
                    ->where('trx_orders_date.date',$date)
                    ->where('status_payment','Success Payment')
                    ->where('packages.type_package',$type_package)
                    ->where('packages.category','like','%'.$row->product_id.'%')
                    ->where('carbo_alternative',$row->carbo_from)
                    ->whereNull('is_paused')
                    ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                    ->get();

                $ttl = 0;
                $ttl_2 = 0;
                $user = [];
                $user_cr = [];
                foreach ($list_cr as $key => $vle) {
                    $off = json_decode($vle->day_off);
                    $off_d = [];
                    if ($date <= $vle->must_end){
                        if ($off){
                            foreach ($off as $y){
                                $off_d[] = $y->day_off;
                            }
                        }else{
                            $off_d = [];
                        }
                        if (!in_array($d,$off_d)){
                            $no_carbo = TrxOrdersDate::simpleQuery()
                                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                                ->where('trx_orders_date.date',$date)
                                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                                ->where('status_payment','Success Payment')
                                ->whereNull('is_paused')
                                ->where('packages.type_package',$type_package)
                                ->where('packages.category','like','%'.$row->product_id.'%')
                                ->where('carbo','like','%'.$row->carbo_from.'%')
                                ->orWhere('carbo',NULL)
                                ->select('carbo')
                                ->groupBy('carbo')
                                ->get();

                            if ($no_carbo) {
                                foreach ($no_carbo as $key => $no) {
                                    if($no->carbo == TRUE) {
                                        $ttl_2 += 1;
                                        $user_cr[] = array(
                                            'customer' => $vle->customer_name,
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
                foreach ($list as $r){
                    $off = json_decode($r->day_off);
                    $off_d = [];
                    if ($date <= $r->must_end){
                        if ($off){
                            foreach ($off as $y){
                                $off_d[] = $y->day_off;
                            }
                        }else{
                            $off_d = [];
                        }
                        if (!in_array($d,$off_d)){

                            $ttl += 1;
                            $user[] = array(
                                'customer' => $r->customer_name
                            );

                        }
                    }
                }


                if ($c->carbo_alternative == NULL ) {
                    $c->carbo_alternative = $row->carbo_from;
                    $ttl = $ttl+$ttl_2;
                    $user = [];
                }

                $car[] = array(
                    'carbo'=>$c->carbo_alternative,
                    'total' => $ttl,
                    'customer'=> $user,
                );
            }

            //alter carbo
            $hincar = [];
            $hincarbo = TrxOrdersDate::simpleQuery()
                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                ->where('trx_orders_date.date',$date)
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->where('status_payment','Success Payment')
                ->whereNull('is_paused')
                ->where('packages.type_package',$type_package)
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->where('carbo_alternative','!=',$row->carbo_from)
                // ->orWhere('carbo_alternative', NULL)
                ->select('carbo_alternative')
                ->groupBy('carbo_alternative')
                ->get();

            foreach ($hincarbo as $key => $c){
                $list = TrxOrdersDate::simpleQuery()
                    ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->where('trx_orders_date.date',$date)
                    ->where('status_payment','Success Payment')
                    ->where('packages.type_package',$type_package)
                    ->where('packages.category','like','%'.$row->product_id.'%')
                    ->where('carbo_alternative',$c->carbo_alternative)
                    ->where('carbo','like','%'.$row->carbo_from.'%')
                    ->whereNull('is_paused')
                    ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                    ->get();

                $list_cr = TrxOrdersDate::simpleQuery()
                    ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->where('trx_orders_date.date',$date)
                    ->where('status_payment','Success Payment')
                    ->where('packages.type_package',$type_package)
                    ->where('packages.category','like','%'.$row->product_id.'%')
                    ->where('carbo_alternative',$row->carbo_from)
                    ->whereNull('is_paused')
                    ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
                    ->get();

                $ttl = 0;
                $ttl_2 = 0;
                $user = [];
                $user_cr = [];
                foreach ($list_cr as $key => $vle) {
                    $off = json_decode($vle->day_off);
                    $off_d = [];
                    if ($date <= $vle->must_end){
                        if ($off){
                            foreach ($off as $y){
                                $off_d[] = $y->day_off;
                            }
                        }else{
                            $off_d = [];
                        }
                        if (!in_array($d,$off_d)){
                            $hinno_carbo = TrxOrdersDate::simpleQuery()
                                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                                ->where('trx_orders_date.date',$date)
                                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                                ->where('status_payment','Success Payment')
                                ->whereNull('is_paused')
                                ->where('packages.type_package',$type_package)
                                ->where('packages.category','like','%'.$row->product_id.'%')
                                ->where('carbo','like','%'.$row->carbo_from.'%')
                                ->orWhere('carbo',NULL)
                                ->select('carbo')
                                ->groupBy('carbo')
                                ->get();
                            if ($hinno_carbo) {
                                foreach ($hinno_carbo as $key => $no) {
                                    if($no->carbo == TRUE) {
                                        $ttl_2 += 1;
                                        $user_cr[] = array(
                                            'customer' => $vle->customer_name
                                        );
                                    }
                                }
                            }
                        }
                    }
                }

                foreach ($list as $r){
                    $off = json_decode($r->day_off);
                    $off_d = [];
                    if ($date <= $r->must_end){
                        if ($off){
                            foreach ($off as $y){
                                $off_d[] = $y->day_off;
                            }
                        }else{
                            $off_d = [];
                        }
                        if (!in_array($d,$off_d)){
                            $ttl += 1;
                            $user[] = array(
                                'customer' => $r->customer_name,
                            );
                        }
                    }
                }

                if ($c->carbo_alternative == NULL ) {
                    $c->carbo_alternative = $row->carbo_from;
                    $ttl = $ttl+$ttl_2;
                    $user = [];
                }

                $hincar[] = array(
                    'hincarbo'=>$c->carbo_alternative,
                    'total' => $ttl,
                    'customer'=> $user,
                );
            }

            $arr[] = array(
                'product_id'=> $row->product_id,
                'menu'=> $row->name,
                'protein' => $pro,
                'hinprotein' => $hinpro,
                'carbo' => $car,
                'hincarbo' => $hincar,
                'alergy'=>$jumlah_alerg,
                'total_box'=>self::getTotalBox($type_package,$row->product_id,$date)
            );
        }

        return $arr;
    }

    public static function getDriverName($driver){
        if ($driver == NULL) {
            return '-';
        }else{
            $result = Drivers::simpleQuery()
            ->where('id',$driver)
            ->first();

            return $result->name;
        }
    }

    public static function getStatusPengiriman($id,$date){
        $query = TrxOrdersStatus::simpleQuery()
        ->where('trx_orders_id',$id)
        ->orderBy('id','desc')
        ->whereDate('date',$date)
        ->select('date','trx_orders_id','status_pengiriman')
        ->first();
        if (!empty($query->status_pengiriman)){
            $return = $query->status_pengiriman;
        }else{
            $return = 'Proses';
        }
        return $return;
    }

    // public static function menuDapur($type_package,$date){
    //     $d = HariApa($date);
    //     $d = strtolower($d);
        
    //     $list_menu = Menus::simpleQuery()
    //     ->where('menu_date',$date)
    //     ->whereNotIn('product_id',['Salad','Snack'])
    //     ->get();

    //     $arr = [];

    //     foreach ($list_menu as $key => $row) {
    //         $alergy = TrxOrders::simpleQuery()
    //         ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
    //         ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
    //         ->join('customers','customers.id','=','trx_orders.customers_id')
    //         ->where('must_end','>=',$date)
    //         ->where('tgl_mulai','<=',$date)
    //         ->where('status_payment','Success Payment')
    //         ->where('packages.type_package',$type_package)
    //         ->where('packages.category','like','%'.$row->product_id.'%')
    //         ->whereNull('is_paused')
    //         ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
    //         ->get();

    //         $jumlah_alergy = [];
    //         $alergy_trx = [];
    //         foreach ($alergy as $a){
    //             $off = json_decode($a->day_off);
    //             $off_d = [];
    //             if ($date <= $a->must_end){
    //                 if ($off){
    //                     foreach ($off as $y){
    //                         $off_d[] = $y->day_off;
    //                     }
    //                 }else{
    //                     $off_d = [];
    //                 }
    //                 if (!in_array($d,$off_d)){
    //                     $check = DB::table('trx_orders_alergy')
    //                     ->leftjoin('master_alergy','trx_orders_alergy.master_alergy_id','=','master_alergy.id')
    //                     ->where('trx_orders_id',$a->id)
    //                     ->select('master_alergy.name','trx_orders_alergy.trx_orders_id as trx_id')
    //                     ->get();

    //                     if ($check) {
    //                         foreach ($check as $key => $c) {
    //                             if(strpos($row->alergy,$c->name) !== false) {
    //                                 $jumlah_alergy[] = array('customer' => $a->customer_name);
    //                                 $alergy_trx[] = array($c->trx_id);
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }

    //         // Protein
    //         $protein = TrxOrders::simpleQuery()
    //         ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
    //         ->whereNotIn('trx_orders.id',$array_trx)
    //         ->whereDate('must_end','>=',$date)
    //         ->whereDate('tgl_mulai','<=',$date)
    //         ->where('status_payment','Success Payment')
    //         ->whereNull('is_paused')
    //         ->where('packages.type_package',$type_package)
    //         ->where('packages.category','like','%'.$row->product_id.'%')
    //         ->where('protein_alternative','!=',$row->protein_from)
    //         ->orWhere('protein_alternative',NULL)
    //         ->select('protein_alternative')
    //         ->groupBy('protein_alternative')
    //         ->get();

    //         $pro = [];

    //         foreach ($protein as $key => $p){
    //             $list = TrxOrders::simpleQuery()
    //             ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
    //             ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
    //             ->join('customers','customers.id','=','trx_orders.customers_id')
    //             ->whereNotIn('trx_orders.id',$array_trx)
    //             ->where('must_end','>=',$date)
    //             ->where('tgl_mulai','<=',$date)
    //             ->where('status_payment','Success Payment')
    //             ->where('packages.type_package',$type_package)
    //             ->where('packages.category','like','%'.$row->product_id.'%')
    //             ->where('protein_alternative',$p->protein_alternative)
    //             ->whereNull('is_paused')
    //             ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
    //             ->get();

    //             $list_pr = TrxOrders::simpleQuery()
    //             ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
    //             ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
    //             ->join('customers','customers.id','=','trx_orders.customers_id')
    //             ->whereNotIn('trx_orders.id',$array_trx)
    //             ->where('must_end','>=',$date)
    //             ->where('tgl_mulai','<=',$date)
    //             ->where('status_payment','Success Payment')
    //             ->where('packages.type_package',$type_package)
    //             ->where('packages.category','like','%'.$row->product_id.'%')
    //             ->where('protein_alternative',$row->protein_from)
    //             ->whereNull('is_paused')
    //             ->select('trx_orders.*','packages.category as package_name','customers.name as customer_name')
    //             ->get();

    //             $ttl = 0;
    //             $ttl_2 = 0;
    //             $user = [];
    //             $user_p = [];
    //             foreach ($list_pr as $key => $vle) {
    //                 $off = json_decode($vle->day_off);
    //                 $off_d = [];
    //                 if ($date <= $vle->must_end){
    //                     if ($off){
    //                         foreach ($off as $y){
    //                             $off_d[] = $y->day_off;
    //                         }
    //                     }else{
    //                         $off_d = [];
    //                     }
    //                     if (!in_array($d,$off_d)){
    //                         $ttl_2 += 1;
    //                         $user_p[] = array(
    //                             'customer' => $vle->customer_name,
    //                         );
    //                     }
    //                 }
    //             }
    //             foreach ($list as $r){
    //                 $off = json_decode($r->day_off);
    //                 $off_d = [];
    //                 if ($date <= $r->must_end){
    //                     if ($off){
    //                         foreach ($off as $y){
    //                             $off_d[] = $y->day_off;
    //                         }
    //                     }else{
    //                         $off_d = [];
    //                     }
    //                     if (!in_array($d,$off_d)){
    //                         $ttl += 1;
    //                         $user[] = array(
    //                             'customer' => $r->customer_name,
    //                         );
    //                     }
    //                 }
    //             }

    //             if ($p->protein_alternative == NULL ) {
    //                 $p->protein_alternative = $row->protein_from;
    //                 $ttl = $ttl+$ttl_2;
    //                 $user = [];
    //             }

    //             $pro[] = array(
    //                 'protein'=>$p->protein_alternative,
    //                 'total' => $ttl,
    //                 'customer'=> $user,
    //             );
    //         }

    //     }
    // }
}