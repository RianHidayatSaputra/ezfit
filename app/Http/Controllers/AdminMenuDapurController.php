<?php namespace App\Http\Controllers;

use App\Models\TrxOrdersDate;
use crocodicstudio\crudbooster\controllers\CBController;
use App\Models\TrxOrders;
use App\Models\MasterAlergy;
use App\Models\Menus;
use Carbon\Carbon;
use App\Repositories\TrxOrdersRepository;
class AdminMenuDapurController extends CBController {


    public function cbInit()
    {
        $this->setTable("menus");
        $this->setPermalink("menu_dapur");
        $this->setPageTitle("Menu Dapur");
        $this->setButtonDelete(false);
        $this->setButtonEdit(false);
        $this->setButtonAdd(false);

        $this->addImage("Photo","photo")->encrypt(true);
        $this->addText("Name","name")->strLimit(150)->maxLength(255);
        $this->addDate("Menu Date","menu_date");
        $this->addText("Alergy","alergy")->strLimit(150)->maxLength(255);
        $this->addText("Protein","protein")->strLimit(150)->maxLength(255);
        $this->addText("Carbo","carbo")->strLimit(150)->maxLength(255);
        $this->addText("Calory","calory")->strLimit(150)->maxLength(255);
        $this->addText("Fat","fat")->strLimit(150)->maxLength(255);
        $this->addText("Gula","gula")->strLimit(150)->maxLength(255);
        $this->addText("Saturated Fat","saturated_fat")->strLimit(150)->maxLength(255);
        $this->addText("Protein From","protein_from")->strLimit(150)->maxLength(255);
        $this->addText("Carbo From","carbo_from")->strLimit(150)->maxLength(255);
        $this->addMoney("Price Hpp","price_hpp");
        $this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
        $this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
    }

    public function getIndexx(){
        $data = [];
        $data['page_title'] = 'Menu Dapur';

        if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
            $date = getMustEnd(date('Y-m-d'),1);
        }else{
            $date = date('Y-m-d');
        }

        $d = HariApa($date);

        if ($d == 'minggu'){
            $date = getMustEnd($date,1);
        }else{
            $date = $date;
        }

        $data['date'] = Carbon::parse($date)->formatLocalized('%A, %d %B %Y');

        $d = HariApa($date);
        $d = strtolower($d);

        $data['menu'] =  Menus::simpleQuery()
            ->where('menu_date',$date)
            ->get();

        $data['menu_regular'] = TrxOrdersRepository::listMenuDapur('Regular',$date);
        $data['menu_propack'] = TrxOrdersRepository::listMenuDapur('Propack',$date);

        $menu_secondary = Menus::simpleQuery()
            ->where('menu_date',$date)
            ->whereIn('product_id',['Salad','Snack'])
            ->get();

        $salad_snack = [];
        foreach ($menu_secondary as $key => $row) {
            $list = TrxOrders::simpleQuery()
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('must_end','>=',$date)
                ->where('tgl_mulai','<=',$date)
                ->where('status_payment','Success Payment')
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->whereNull('is_paused')
                ->select('trx_orders.*','customers.name as cname')
                ->get();

            $total_menu = 0;
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
                        $total_menu += 1;
                    }
                }
            }

            $salad_snack[] = array(
                'menu_name' => $row->product_id,
                'total' => $total_menu
            );
        }

        $data['menu_secondary'] = $salad_snack;

        return view('backend.menudapur2.menuDapur2',$data);
    }

    public function getExport(){
        $data = [];
        $data['page_title'] = 'Menu Dapur';

        if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
            $date = getMustEnd(date('Y-m-d'),1);
        }else{
            $date = date('Y-m-d');
        }

        $d = HariApa($date);
        if ($d == 'minggu'){
            $date = getMustEnd($date,1);
        }else{
            $date = $date;
        }

        $d = HariApa($date);
        $d = strtolower($d);

        $data['menu_regular'] = TrxOrdersRepository::listMenuDapur('Regular',$date);
        $data['menu_propack'] = TrxOrdersRepository::listMenuDapur('Propack',$date);

        $menu_secondary = Menus::simpleQuery()
            ->where('menu_date',$date)
            ->whereIn('product_id',['Salad','Snack'])
            ->get();

        $salad_snack = [];
        foreach ($menu_secondary as $key => $row) {
            $list = TrxOrders::simpleQuery()
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('must_end','>=',$date)
                ->where('tgl_mulai','<=',$date)
                ->where('status_payment','Success Payment')
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->whereNull('is_paused')
                ->select('trx_orders.*','customers.name as cname')
                ->get();

            $total_menu = 0;
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
                        $total_menu += 1;
                    }
                }
            }

            $salad_snack[] = array(
                'menu_name' => $row->product_id,
                'total' => $total_menu
            );
        }

        $data['menu_secondary'] = $salad_snack;

        return view('backend.menudapur2.menuDapur2',$data);
    }

    public function getIndex(){

        $data = [];
        $data['page_title'] = 'Menu Dapur';

        if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
            $date = getMustEnd(date('Y-m-d'),1);
        }else{
            $date = getMustEnd(date('Y-m-d'),0);
        }

        $d = HariApa($date);
        if ($d == 'minggu'){
            $date = getMustEnd($date,1);
        }else{
            $date = $date;
        }

        $data['date'] = Carbon::parse($date)->formatLocalized('%A, %d %B %Y');

        $d = HariApa($date);
        $d = strtolower($d);

        $data['menu'] =  Menus::simpleQuery()
            ->where('menu_date',$date)
            ->get();

        $data['menu_regular'] = TrxOrdersRepository::listMenuDapur('Regular',$date);
        $data['menu_propack'] = TrxOrdersRepository::listMenuDapur('Propack',$date);

        if (g('debug') == 1){
            dd($data['menu_regular']);
        }

        $menu_secondary = Menus::simpleQuery()
            ->where('menu_date',$date)
            ->whereIn('product_id',['Salad','Snack'])
            ->get();

        $salad_snack = [];
        foreach ($menu_secondary as $key => $row) {
            $list = TrxOrdersDate::simpleQuery()
                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('trx_orders_date.date',$date)
                ->where('status_payment','Success Payment')
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->whereNull('is_paused')
                ->select('trx_orders.*','customers.name as cname')
                ->get();

            $total_menu = 0;
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
                        $total_menu += 1;
                    }
                }
            }

            $salad_snack[] = array(
                'menu_name' => $row->product_id,
                'total' => $total_menu
            );
        }

        $data['menu_secondary'] = $salad_snack;

        return view('backend.menudapur2.menuDapur2',$data);
    }

    public function getTomorrow(){
        $data = [];
        $data['page_title'] = 'Menu Dapur';

        if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
            $date = getMustEnd(date('Y-m-d'),2);
        }else{
            $date = getMustEnd(date('Y-m-d'),1);
        }

        $d = HariApa($date);
        if ($d == 'minggu'){
            $date = getMustEnd($date,1);
        }else{
            $date = $date;
        }

        $data['date'] = Carbon::parse($date)->formatLocalized('%A, %d %B %Y');

        $d = HariApa($date);
        $d = strtolower($d);

        $data['menu'] =  Menus::simpleQuery()
            ->where('menu_date',$date)
            ->get();

        $data['menu_regular'] = TrxOrdersRepository::listMenuDapur('Regular',$date);
        $data['menu_propack'] = TrxOrdersRepository::listMenuDapur('Propack',$date);

        $menu_secondary = Menus::simpleQuery()
            ->where('menu_date',$date)
            ->whereIn('product_id',['Salad','Snack'])
            ->get();
        $salad_snack = [];
        foreach ($menu_secondary as $key => $row) {
            $list = TrxOrdersDate::simpleQuery()
                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('trx_orders_date.date',$date)
                ->where('status_payment','Success Payment')
                ->where('packages.category','like','%'.$row->product_id.'%')
                ->whereNull('is_paused')
                ->select('trx_orders.*','customers.name as cname')
                ->get();

            $total_menu = 0;
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
                        $total_menu += 1;
                    }
                }
            }

            $salad_snack[] = array(
                'menu_name' => $row->product_id,
                'total' => $total_menu
            );
        }

        $data['menu_secondary'] = $salad_snack;

        return view('backend.menudapur2.menuDapur2',$data);
    }
}
