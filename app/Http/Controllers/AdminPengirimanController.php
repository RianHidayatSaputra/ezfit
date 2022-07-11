<?php namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\LogNotice;
use App\Models\TrxOrdersAlergy;
use App\Models\TrxOrdersDate;
use App\Models\TrxOrdersStatus;
use Illuminate\Support\Facades\DB;
use App\Models\Drivers;
use App\Models\TrxOrders;
use App\Models\Menus;
use crocodicstudio\crudbooster\controllers\CBController;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Repositories\VouchersRepository;

class AdminPengirimanController extends CBController {


    public function cbInit()
    {
        $this->setTable("trx_orders");
        $this->setPermalink("pengiriman");
        $this->setPageTitle("Pengiriman");
        $this->setButtonDelete(false);
        $this->setButtonEdit(true);
        $this->setButtonAdd(false);

        $this->addText("No Order","no_order")->strLimit(150)->maxLength(255);
        $this->addSelectTable("Nama Customer","customers_id",["table"=>"customers","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
        $this->addText("Periode","periode")
            ->indexDisplayTransform(function ($row) {
                return $row.' days';
            })->columnWidth(200);
        $this->addText("Tgl Mulai","tgl_mulai")
            ->indexDisplayTransform(function ($row) {
                return date('d M Y',strtotime($row));
            })->columnWidth(200);
        $this->addSelectTable("Package","packages_id",["table"=>"packages","value_option"=>"id","display_option"=>"name","sql_condition"=>""])->columnWidth(200);
        $this->addText("Days Left","id")
            ->indexDisplayTransform(function ($row) {
                $order = TrxOrders::findById($row);
                $end = date('Y-m-d',strtotime('+'.$order->getPeriode().' days',strtotime($order->getTglMulai())));
                $d = dateDiff($order->getMustEnd(),$order->getTglMulai(),$order->getDayOff());
                if ($d <= 0){
                    $return = '<label class="label label-danger">Expired</label>';
                }else{
                    $return = $d.' days';
                }
                return $return;
            });
        $this->addText("Nominal","price")
            ->indexDisplayTransform(function ($row) {
                return "<span style='text-transform: capitalize'> Rp.".number_format($row)."</span>";
            });
        $this->addText("Metode Pembayaran","payment_method")
            ->indexDisplayTransform(function ($row) {
                return "<span style='text-transform: capitalize'>".$row."</span>";
            });
        $this->addText("Status Pembayaran","status_payment")->strLimit(150)->maxLength(255);
    }
    public function getDetailPengiriman(){
        $find = TrxOrders::simpleQuery()
            ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
            ->join('customers','customers.id','=','trx_orders.customers_id')
            ->select('trx_orders.*','packages.name as package_name','customers.name as c_name','customers.ho_hp as c_ho_hp')
            ->where('trx_orders.id',g('id'))
            ->first();

        if (!$find->nama_penerima){
            $find->nama_penerima = $find->c_name;
        }
        if (!$find->no_penerima){
            $find->no_penerima = $find->c_ho_hp;
        }

        return response()->json($find);
    }
    public function getIndex(){
        $data['page_title'] = 'Pengiriman';
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

        $data['menu'] =  Menus::simpleQuery()
            ->where('menu_date',$date)
            ->count();

        $find = DB::table('users')->where('id',auth()->id())->first();

        $data['driver'] = Drivers::simpleQuery()->get();
        $list = TrxOrdersDate::simpleQuery()
            ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
            ->leftjoin('drivers as d_1','d_1.id','=','trx_orders.drivers_id')
            ->leftjoin('drivers as d_2','d_2.id','=','trx_orders.drivers_id_second')
            ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
            ->join('customers','customers.id','=','trx_orders.customers_id')
            ->where('trx_orders_date.date',$date)
            ->where('status_payment','Success Payment')
            ->whereNull('is_paused');

        $driver = Drivers::findBy('users_id',$find->id);

        $list = $list->select('trx_orders.*','d_1.name as driver_name','d_2.name as driver_name_altf','packages.name as package_name','customers.name as c_name','customers.ho_hp as c_ho_hp');


        if (g('kurir')) {
            if (g('kurir') != 'All') {
                $list = $list->where('drivers_id',g('kurir'));
            }
        }

        $list = $list->get();
        $arr = [];

        foreach ($list as $row){
            if (!$row->nama_penerima){
                $row->nama_penerima = $row->c_name;
            }
            if (!$row->no_penerima){
                $row->no_penerima = $row->c_ho_hp;
            }
            $off = json_decode($row->day_off);
            $off_d = [];
            if ($date <= $row->must_end){
                if ($off){
                    foreach ($off as $y){
                        $off_d[] = $y->day_off;
                    }
                }else{
                    $off_d = [];
                }
                if (!in_array($d,$off_d)){
                    $check_status = TrxOrdersStatus::simpleQuery()
                        ->whereDate('date',$date)
                        ->where('trx_orders_id',$row->id)
                        ->orderBy('id','desc')
                        ->first();

                    if ($check_status){
                        $status = $check_status->status_pengiriman;
                    }else{
                        $status = 'Proses';
                    }

                    if ($status == 'Selesai'){
                        $photo_pengiriman = asset($check_status->photo_pengiriman);
                        $nama_penerima_pesanan = $check_status->penerima_pengiriman;
                        $catatan_driver = $check_status->catatan_pengiriman;
                    }else{
                        $photo_pengiriman = '';
                        $nama_penerima_pesanan = '';
                        $catatan_driver = '';
                    }

                    $alt = explode(",",$row->day_for_altf);

                    if (in_array($d,$alt)){
                        $alamat = $row->address_second;
                        $detail_address = $row->detail_address_second;
                        $type_alamat = 2;
                        $driver_now = $row->drivers_id_second;
                        $driver_name = $row->driver_name_altf;
                    }else{
                        $alamat = $row->address;
                        $detail_address = $row->detail_address;
                        $type_alamat = 1;
                        $driver_now = $row->drivers_id;
                        $driver_name = $row->driver_name;
                    }

                    $get_alergy = TrxOrdersAlergy::simpleQuery()
                        ->where('trx_orders_id',$row->id)
                        ->join('master_alergy','master_alergy.id','=','trx_orders_alergy.master_alergy_id')
                        ->select('name')
                        ->get();
                    $alergy = '';
                    foreach ($get_alergy as $a){
                        $alergy = strtolower($a->name).',';
                    }
                    if ($find->cb_roles_id == 3){
                        if ($driver->getId() == $driver_now){
                            $check_libur = DB::table('trx_orders_pause_date')->where('date',$date)->where('trx_orders_id',$row->id)->count();
                            if ($check_libur == 0) {
                                $arr[] = array(
                                    'id'=>$row->id,
                                    'date'=>$date,
                                    'no_order' => $row->no_order,
                                    'package_name' => $row->package_name,
                                    'nama_penerima' => $row->nama_penerima,
                                    'no_penerima' => $row->no_penerima,
                                    'address' => $alamat,
                                    'detail_address' => $detail_address,
                                    'protein' => $row->protein,
                                    'carbo' => $row->carbo,
                                    'price' => $row->price,
                                    'driver_name'=>$driver_name,
                                    'day_off' => $off_d,
                                    'status_pengiriman' => $status,
                                    'photo_pengiriman' => $photo_pengiriman,
                                    'nama_penerima_pesanan' => $nama_penerima_pesanan,
                                    'catatan_driver' => $catatan_driver,
                                    'alargy'=> rtrim($alergy,", "),
                                    'type_alamat' => $type_alamat,
                                );
                            }
                        }
                    }else{
                        $check_libur = DB::table('trx_orders_pause_date')->where('date',$date)->where('trx_orders_id',$row->id)->count();
                        if ($check_libur == 0) {
                            $arr[] = array(
                                'id'=>$row->id,
                                'date'=>$date,
                                'no_order' => $row->no_order,
                                'package_name' => $row->package_name,
                                'nama_penerima' => $row->nama_penerima,
                                'no_penerima' => $row->no_penerima,
                                'address' => $alamat,
                                'detail_address' => $detail_address,
                                'protein' => $row->protein,
                                'carbo' => $row->carbo,
                                'price' => $row->price,
                                'driver_name'=>$driver_name,
                                'day_off' => $off_d,
                                'status_pengiriman' => $status,
                                'photo_pengiriman' => $photo_pengiriman,
                                'nama_penerima_pesanan' => $nama_penerima_pesanan,
                                'catatan_driver' => $catatan_driver,
                                'alargy'=> rtrim($alergy,", "),
                                'type_alamat' => $type_alamat,
                            );
                        }
                    }

                }
            }
        }
        $data['list'] = $arr;
        return view('backend.pengiriman.index',$data);
    }

    public function postUpdateDriver(){
        $id = g('id');

        $find = TrxOrders::findById($id);
        $find->setDriversId(g('drivers_id'));
        $find->save();

        $check = TrxOrdersStatus::simpleQuery()
            ->where('date',date('Y-m-d'))
            ->where('trx_orders_id',$id)
            ->where('status_pengiriman','Proses')
            ->first();

        if ($check){
            TrxOrdersStatus::simpleQuery()->where('id',$check->id)->delete();

            $new = new TrxOrdersStatus();
            $new->setTrxOrdersId($id);
            $new->setStatusPengiriman('Proses');
            $new->setDate(date('Y-m-d'));
            $new->save();

            $content = 'Katering kamu hari ini sedang diproses, harap bersabar menunggu ya.';
            $mess['type'] = 'order';
            $mess['id_order'] = $find->getId();
            $mess['content'] = $content;
            $mess['type_notice'] = 'ongoing';
            $data['title'] = 'Status Pengiriman';
            $data['content'] = $content;
            $data['data'] = $mess;

            $regid_a[] = $find->getCustomersId()->getRegid();
            $regid_i[] = $find->getCustomersId()->getRegidIos();
            $logs[] = SendFcm($regid_a,$data,'IOS');
            $logs[] = SendFcm($regid_i,$data,'IOS');

            $log = new LogNotice();
            $log->setCustomersId($find->getCustomersId()->getId());
            $log->setTrxOrdersId($find->getId());
            $log->setType('pengiriman');
            $log->setTitle('Status Pengiriman');
            $log->setTypeNotice('ongoing');
            $log->setContent($content);
            $log->setCreatedAt(date('Y-m-d H:i:s'));
            $log->save();
        }

        return redirect()->back()->with(["message_type"=>'success','message'=>'Success update kurir Order']);
    }
    public function postUpdateStatus(){
        $order = TrxOrders::findById(g('trx_orders_id'));
        $customer = Customers::findById($order->getCustomersId()->getId());
        $u['trx_orders_id'] = g('trx_orders_id');
        $u['status_pengiriman'] = g('status_pengiriman');
        $u['date'] = date('Y-m-d');
        if (g('status_pengiriman') == 'Selesai'){
            if (g('photo_pengiriman')){
                $u['photo_pengiriman'] = CB()->uploadFile('photo_pengiriman',true);
            }
            $u['catatan_pengiriman'] = g('catatan_pengiriman');
        }
        $data['title'] = 'Update Status Pengiriman';
        $mess['type'] = 'order_pengiriman';
        if (g('status_pengiriman') == 'Dikirim'){
            $content = 'Horee, paket kateringmu akan dikirim kurir ke tempatmu. Siap-siap untuk menerimanya ya.';
        }else{
            $content = 'Yey!!, kateringmu sudah berhasil dikirim. Selamat menikmati katering sehat dari Ez Fit.';
        }
        $mess['content'] = $content;
        $mess['id_order'] = $order->getId();
        $mess['type_notice'] = 'ongoing';
        $data['content'] = $content;
        $data['data'] = $mess;

        $regid_ios = [];
        $regid_android = [];
        $regid_ios = array($customer->getRegidIos());
        $regid_android = array($customer->getRegid());
        if ($customer->getRegid()){
            $android = SendFcm($regid_android,$data,'IOS');
        }
        if ($customer->getRegidIos()){
            $ios = SendFcm($regid_ios,$data,'IOS');
        }
        $success = DB::table('trx_orders_status')->insert($u);
        if ($success){
            $log = new LogNotice();
            $log->setCustomersId($order->getCustomersId()->getId());
            $log->setTrxOrdersId(g('trx_orders_id'));
            $log->setContent($content);
            $log->save();
        }
        return redirect()->back()->with(["message_type"=>'success','message'=>'Success update status Order']);
    }
    public function getStatus(){
        $id = g('id');

        $find = TrxOrdersStatus::simpleQuery()
            ->where('trx_orders_id',$id)
            ->orderBy('id','desc')
            ->first();
        if ($find){
            $find->photo_pengiriman = asset($find->photo_pengiriman);
        }

        return response()->json($find);
    }

    public function getExportMenu(){
        $data['page_title'] = 'Report Menu Pengiriman';


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

        $data['menu'] =  Menus::simpleQuery()
            ->where('menu_date',$date)
            ->get();

        $arr = [];
        foreach ($data['menu'] as $key => $val) {
            $list = TrxOrdersDate::simpleQuery()
                ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
                ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('trx_orders_date.date',$date)
                ->where('status_payment','Success Payment')
                ->whereNull('is_paused')
                ->where('packages.name','like','%'.$val->product_id.'%')
                ->select('trx_orders.*','packages.type_package as type_package')
                ->get();

            $arr = [];
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
                        $arr[] = array('type' => $aria->type_package);
                    }
                }
            }

            $val->package_list = $arr;
        }

        return view('backend.pengiriman.menu',$data);
    }
    public function getExport(){
        $data['page_title'] = 'Report Pengiriman';
        $find = DB::table('users')->where('id',auth()->id())->first();

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


        $data['driver'] = Drivers::simpleQuery()->get();
        $list = TrxOrdersDate::simpleQuery()
            ->leftJoin('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
            ->leftjoin('drivers as d_1','d_1.id','=','trx_orders.drivers_id')
            ->leftjoin('drivers as d_2','d_2.id','=','trx_orders.drivers_id_second')
            ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
            ->join('customers','customers.id','=','trx_orders.customers_id')
            ->where('trx_orders_date.date',$date)
            ->where('status_payment','Success Payment')
            ->whereNull('is_paused');

        if ($find->cb_roles_id == 3){
            $driver = Drivers::findBy('users_id',$find->id);
            $list = $list->where('drivers_id',$driver->getId());
        }

        $list = $list->select('trx_orders.*','d_1.no_wa as no_wa','d_2.no_wa as no_wa_altf','d_1.name as driver_name','d_2.name as driver_name_altf','packages.name as package_name','customers.name as c_name','customers.ho_hp as c_ho_hp')
            ->get();

        $arr = [];

        $lunch_today =  Menus::simpleQuery()
            ->where('menu_date',$date)
            ->where('product_id','Lunch')
            ->first();

        $dinner_today =  Menus::simpleQuery()
            ->where('menu_date',$date)
            ->where('product_id','Dinner')
            ->first();

        foreach ($list as $row){
            if (!$row->nama_penerima){
                $row->nama_penerima = $row->c_name;
            }
            if (!$row->no_penerima){
                $row->no_penerima = $row->c_ho_hp;
            }
            $off = json_decode($row->day_off);
            $off_d = [];
            if ($date <= $row->must_end){
                if ($off){
                    foreach ($off as $y){
                        $off_d[] = $y->day_off;
                    }
                }else{
                    $off_d = [];
                }
                if (!in_array($d,$off_d)){
                    if ($lunch_today->protein_from != $row->protein_alternative) {
                        $protein_rk_pl = $row->protein_alternative;
                    }else{
                        $protein_rk_pl = '';
                    }

                    if ($lunch_today->carbo_from != $row->carbo_alternative) {
                        $carbo_rk_cl = $row->carbo_alternative;
                    }else{
                        $carbo_rk_cl = '';
                    }

                    if ($dinner_today->protein_from != $row->protein_alternative) {
                        $protein_rk_pd = $row->protein_alternative;
                    }else{
                        $protein_rk_pd = '';
                    }

                    if ($dinner_today->carbo_from != $row->carbo_alternative) {
                        $carbo_rk_cd = $row->carbo_alternative;
                    }else{
                        $carbo_rk_cd = '';
                    }

                    $check_status = TrxOrdersStatus::simpleQuery()
                        ->whereDate('date',$date)
                        ->where('trx_orders_id',$row->id)
                        ->orderBy('id','desc')
                        ->first();
                    if ($check_status){
                        $status = $check_status->status_pengiriman;
                    }else{
                        $status = '';
                    }
                    if ($status == 'Selesai'){
                        $photo_pengiriman = asset($check_status->photo_pengiriman);
                        $nama_penerima_pesanan = $check_status->penerima_pengiriman;
                        $catatan_driver = $check_status->catatan_pengiriman;
                    }else{
                        $photo_pengiriman = '';
                        $nama_penerima_pesanan = '';
                        $catatan_driver = '';
                    }

                    $get_alergy = TrxOrdersAlergy::simpleQuery()
                        ->where('trx_orders_id',$row->id)
                        ->join('master_alergy','master_alergy.id','=','trx_orders_alergy.master_alergy_id')
                        ->select('name')
                        ->get();
                    $alergy_l = '';
                    $alergy_d = '';
                    foreach ($get_alergy as $a){
                        if (strpos($lunch_today->alergy, $a->name) !== FALSE) {
                            $alergy_l = ucwords($a->name).',';
                        }
                        if (strpos($dinner_today->alergy, $a->name) !== FALSE) {
                            $alergy_d = ucwords($a->name).',';
                        }
                    }

                    if ($alergy_l == '') {
                        $alergy_l = '-';
                    }else{
                        $alergy_l = rtrim($alergy_l,", ");
                    }

                    if ($alergy_d == '') {
                        $alergy_d = '-';
                    }else{
                        $alergy_d = rtrim($alergy_d,", ");
                    }

                    $data['menu'] =  Menus::simpleQuery()
                        ->where('menu_date',$date)
                        ->first();
                    if ($row->protein_alternative == NULL || $row->carbo_alternative == NULL) {
                        $tor = '';
                    }else{
                        $tor = ', ';
                    }
                    $alt = explode(",",$row->day_for_altf);

                    if (in_array($d,$alt)){
                        $nama_alamat = $row->address_name_second;
                        $alamat = $row->address_second;
                        $detail_address = $row->detail_address_second;
                        $type_alamat = 2;
                        $driver_now = $row->drivers_id_second;
                        $driver_name = $row->driver_name_altf;
                        $driver_no = $row->no_wa_altf;
                        $catatan = $row->catatan_altf;
                    }else{
                        $nama_alamat = $row->address_book_id;
                        $alamat = $row->address;
                        $detail_address = $row->detail_address;
                        $type_alamat = 1;
                        $driver_now = $row->drivers_id;
                        $driver_name = $row->driver_name;
                        $driver_no = $row->no_wa;
                        $catatan = $row->catatan;
                    }

                    $arr[] = array(
                        'id'=>$row->id,
                        'date'=>Carbon::parse($date)->format('d F Y'),
                        'id_order' => $row->no_order,
                        'nama' => $row->nama_penerima,
                        'no_hp' => $row->no_penerima,
                        'package' => $row->package_name,
                        'request_khusus_p_l' => $protein_rk_pl,
                        'request_khusus_p_d' => $protein_rk_pd,
                        'request_khusus_c_l' => $carbo_rk_cl,
                        'request_khusus_c_d' => $carbo_rk_cd,
                        'alergen_l'=> $alergy_l,
                        'alergen_d'=> $alergy_d,
                        'address' => $nama_alamat.' '.$alamat,
                        'detail_address' => $detail_address,
                        'kurir_hp' => $driver_name.' / '.$driver_no,
                        'catatan' => $catatan,
                    );
                }
            }
        }
        $data['row'] = $arr;
        return view('backend.pengiriman.report',$data);
    }
    public function postPackingData(){
        $data['page_title'] = 'Export Data Packing';
        $list = TrxOrders::simpleQuery()
            ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
            ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
            ->join('customers','customers.id','=','trx_orders.customers_id')
            ->where('must_end','>=',date('Y-m-d'))
            ->where('tgl_mulai','<=',date('Y-m-d'))
            ->where('status_payment','Success Payment');

        if (g('kurir_pd') != 'All') {
            $list = $list->where('drivers_id',g('kurir_pd'));
        }

        $list = $list->whereNull('is_paused')
            ->select('trx_orders.*','packages.type_package','packages.name as packages','customers.name as customer','customers.ho_hp as telp','drivers.name as driver')
            ->get();

        foreach ($list as $key => $row) {
            $alergy = TrxOrdersAlergy::simpleQuery()
                ->leftjoin('master_alergy','trx_orders_alergy.master_alergy_id','=','master_alergy.id')
                ->where('trx_orders_id',$row->id)
                ->select('master_alergy.name')
                ->get();

            $alrg = [];
            foreach ($alergy as $key => $a) {
                $alrg[] = array('name' => $a->name);
            }

            $row->alergy = $alrg;
        }
        $data['row'] = $list;

        return view('backend.pengiriman.data',$data);
    }

    public function getFinishDetail(){
        $id = g('id');
        $date = g('date');

        $query = TrxOrdersStatus::simpleQuery()
            ->where('trx_orders_id',$id)
            ->whereDate('date',$date)
            ->where('status_pengiriman','Selesai')
            ->orderBy('created_at')
            ->select('photo_pengiriman as img','catatan_pengiriman as driver_note')
            ->first();

        if ($query->driver_note == NULL) {
            $query->driver_note = 'Tidak Ada Catatan.';
        }

        $query->img = asset($query->img);

        return response()->json($query);
    }

    public function postKirim(){
        $arr = g('id');

        foreach ($arr as $row) {
            $validation = TrxOrdersStatus::simpleQuery()
                ->where('trx_orders_id',$row)
                ->where('status_pengiriman','Dikirim')
                ->whereDate('date',date('Y-m-d'))
                ->first();

            if (!$validation) {
                $order = TrxOrders::findById($row);
                $customer = Customers::findById($order->getCustomersId()->getId());
                $u['trx_orders_id'] = $row;
                $u['status_pengiriman'] = 'Dikirim';
                $u['date'] = date('Y-m-d');
                $u['created_at'] = date('Y-m-d H:i:s');

                DB::table('trx_orders_status')->insert($u);
            }
        }

        $result = [];
        return response()->json($result);
    }

    public function getRun(){
        $check = DB::table('this_day_log')->where('date',date('Y-m-d'))->count();
        if($check == 0 ){
            $order = TrxOrders::simpleQuery()
                ->leftjoin(
                    DB::raw("(SELECT trx_orders_status.trx_orders_id,count(trx_orders_status.id) as total_pengiriman 
                    FROM trx_orders_status GROUP BY trx_orders_status.trx_orders_id) as total_pengiriman"),
                    "total_pengiriman.trx_orders_id","=","trx_orders.id")
                ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('must_end','>=',date('Y-m-d'))
                ->where('tgl_mulai','<=',date('Y-m-d'))
                ->select('trx_orders.*',
                    'customers.regid as regid',
                    'customers.regid_ios as regid_ios',
                    'customers.name as cs_name',
                    'total_pengiriman'
                )
                ->get();


            $regids = [];
            $regid_ioss = [];
            foreach ($order as $l){
                if(($l->periode-$l->total_pengiriman) == 3 OR ($l->periode-$l->total_pengiriman) == 2 OR ($l->periode-$l->total_pengiriman) == 1){
                    if ($l->regid){
                        $regids[] = $l->regid;
                    }
                    if ($l->regid_ios){
                        $regid_ioss[] = $l->regid_ios;
                    }
                }
            }

            $data_expired['title'] = 'Pemberitahuan Order';
            $cont = 'Status pembelian anda akan segera selesai';
            $messde['content'] = $cont;
            $messde['type']   = 'order_expired';
            $messde['type_notice'] = 'ongoing';
            $data_expired['content'] = $cont;
            $data_expired['data'] = $messde;
            if ($regids){
                SendFcm($regids,$data_expired,'IOS');
            }
            if ($regid_ioss){
                SendFcm($regid_ioss,$data_expired,'IOS');
            }
            $inse['date'] = date('Y-m-d');
            DB::table('this_day_log')->insert($inse);
        }

        // mahasiswa expired
        $mhs = Customers::simpleQuery()
            ->where('type_customer','mahasiswa')
            ->whereNotNull('start_date')
            ->where('end_date','>=',date('Y-m-d'))
            ->get();
        $regid = [];
        $id_reg = [];
        $regidIos = [];
        foreach ($mhs as $y){
            if ($y->end_date <= date('Y-m-d')){
                $regid[] = $y->regid;
                $regidIos[] = $y->regid_ios;
                $id_reg[] = $y->id;
            }
        }
        if($id_reg){
            $update_status['type_customer'] = 'umum';
            $update_status['photo_krs'] = NULL;
            $update_status['photo_ktm'] = NULL;

            DB::table('customers')->wherein('id',$id_reg)->update($update_status);
        }

        $data_account['title'] = 'Account Mahasiswa Expired';
        $conten_e = 'Status mahasiswa pada akun anda sudah kadaluarsa silahkan perbarui';
        $messme['content'] = $conten_e;
        $messme['type']   = 'account';
        $data_account['content'] = $conten_e;
        $data_account['content'] = $messme;
        if ($regid){
            SendFcm($regid,$data_account,'IOS');
        }
        if($regidIos){
            SendFcm($regidIos,$data_account,'IOS');
        }

        // order update
        if (date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')){
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

        $list = TrxOrders::simpleQuery()
            ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
            ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
            ->leftjoin('customers','customers.id','=','trx_orders.customers_id')
            ->where('trx_orders.status_payment','!=','Failed')
            ->where('must_end','>=',$date)
            ->where('tgl_mulai','<=',$date)
            ->select(
                'trx_orders.*',
                'drivers.name as driver_name',
                'packages.name as package_name',
                'customers.name as c_name',
                'customers.ho_hp as c_ho_hp',
                'customers.regid as regid',
                'customers.regid_ios as regid_ios'
            )
            ->get();

        $arr = [];

        $d = HariApa($date);
        $d = strtolower($d);
        $regid_a = [];
        $regid_i = [];
        foreach ($list as $row){
            if ($row->status_payment != 'Success Payment'){
//                if ($row->tgl_mulai == $date){
//                    $up['status_payment'] = 'Failed';
//                    DB::table('trx_orders')->where('id',$row->id)->update($up);
//                    if ($row->vouchers_code != NULL) {
//                        VouchersRepository::addQuota($row->vouchers_code);
//                    }
//                }
            }elseif ($row->status_payment == 'Success Payment'){
                if (!$row->nama_penerima){
                    $row->nama_penerima = $row->c_name;
                }
                if (!$row->no_penerima){
                    $row->no_penerima = $row->c_ho_hp;
                }
                $off = json_decode($row->day_off);
                $off_d = [];
                if ($date <= $row->must_end){
                    if ($off){
                        foreach ($off as $y){
                            $off_d[] = $y->day_off;
                        }
                    }else{
                        $off_d = [];
                    }
                    if (!in_array($d,$off_d)){
                        if ($row->is_paused == 1){
                            // hari normal jika di pause
                            $edded_date = date('Y-m-d',date(strtotime("+1 day", strtotime($row->must_end))));
                            if (HariApa($edded_date) == 'minggu'){
                                // check if hari minggu
                                $edded_date = date('Y-m-d',date(strtotime("+1 day", strtotime($edded_date))));
                            }
                            $add = 0; // tambah jika libur
                            if (!empty($off)){
                                foreach ($off as $y){
                                    if (HariApa($edded_date) == $y->day_off){
                                        $add += 1;
                                    }
                                }
                            }
                            $edded_date = date('Y-m-d',date(strtotime("+".$add." day", strtotime($edded_date))));
                            if (date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')) {
                                $up['must_end'] = $edded_date;
                                DB::table('trx_orders')->where('id', $row->id)->update($up);

                                $insert['trx_orders_id'] = $row->id;
                                $insert['date'] = $date;
                                $insert['created_at'] = date('Y-m-d H:i:s');

                                DB::table('trx_orders_pause_date')->insert($insert);
                            }
                        }else{
                            if (date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')){
                                $check = DB::table('trx_orders_status')
                                    ->where('trx_orders_id',$row->id)
                                    ->where('status_pengiriman','Proses')
                                    ->whereDate('created_at', date('Y-m-d'))
                                    ->count();
                                if ($check == 0){
                                    $in['trx_orders_id'] = $row->id;
                                    $in['status_pengiriman'] = 'Proses';
                                    $in['date'] = $date;
                                    $in['created_at'] = date('Y-m-d H:i:s');

                                    DB::table('trx_orders_status')->insert($in);

                                    $content = 'Katering kamu hari ini sedang diproses, harap bersabar menunggu ya.';
                                    $mess['type'] = 'order';
                                    $mess['id_order'] = $row->id;
                                    $mess['content'] = $content;
                                    $mess['type_notice'] = 'ongoing';
                                    $data['title'] = 'Status Pembayaran';
                                    $data['content'] = $content;
                                    $data['data'] = $mess;

                                    $regid_a[] = $row->regid;
                                    $regid_i[] = $row->regid_ios;

                                    $log = new LogNotice();
                                    $log->setCustomersId($row->customers_id);
                                    $log->setTrxOrdersId($row->id);
                                    $log->setTitle('Status Pengiriman');
                                    $log->setTypeNotice('ongoing');
                                    $log->setContent($content);
                                    $log->setCreatedAt(date('Y-m-d H:i:s'));
                                    $log->save();
                                }
                            }
                        }
                    }
                }
            }elseif($row->status_payment == 'Waiting Payment'){
                if (date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')){
                    $check = LogNotice::simpleQuery()
                        ->where('customers_id',$row->customers_id)
                        ->where('trx_orders_id',$row->id)
                        ->where('title','Orders Awaiting Payment')
                        ->count();
                    if($check == 0){
                        $content = 'Pembayaran katering anda tertunda, apakah anda ingin melanjutkan pembayaran ?.';
                        $mess['type'] = 'order';
                        $mess['id_order'] = $row->id;
                        $mess['content'] = $content;
                        $mess['type_notice'] = 'ongoing';
                        $data['title'] = 'Orders Awaiting Payment';
                        $data['content'] = $content;
                        $data['data'] = $mess;

                        $regid_a[] = $row->regid;
                        $regid_i[] = $row->regid_ios;

                        $log = new LogNotice();
                        $log->setCustomersId($row->customers_id);
                        $log->setTrxOrdersId($row->id);
                        $log->setTitle('Orders Awaiting Payment');
                        $log->setTypeNotice('ongoing');
                        $log->setContent($content);
                        $log->setCreatedAt(date('Y-m-d H:i:s'));
                        $log->save();
                    }
                }
            }
        }
        if ($regid_a){
            $logs[] = SendFcm($regid_a,$data,'IOS');
        }
        if($regid_i){
            $logs[] = SendFcm($regid_i,$data,'IOS');
        }
        return redirect()->back()->with(["message_type"=>'success','message'=>'Success!!']);
    }
}