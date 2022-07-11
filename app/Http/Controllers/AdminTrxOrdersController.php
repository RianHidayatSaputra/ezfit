<?php namespace App\Http\Controllers;

use App\Imports\OrderCustomer;
use App\Models\TrxOrdersAlergy;
use App\Models\TrxOrdersDate;
use App\Models\TrxOrdersStatus;
use App\Models\TrxOrdersPauseDate;
use App\Repositories\AddressBookRepository;
use App\Repositories\CustomersRepository;
use App\Repositories\TrxOrdersRepository;
use App\Repositories\VouchersRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Customers;
use App\Models\Drivers;
use App\Models\MasterAlergy;
use App\Models\MsCarbon;
use App\Models\MsProtein;
use App\Models\Packages;
use App\Models\TrxOrders;
use App\Models\Vouchers;
use App\Repositories\PackagesRepository;
use crocodicstudio\crudbooster\controllers\CBController;
use Carbon\Carbon;
use App\Models\LogNotice;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AddressBook;
use Illuminate\Database\Query\Builder;
use crocodicstudio\crudbooster\controllers\partials\ButtonColor;

class AdminTrxOrdersController extends CBController {

    public function cbInit()
    {
        $this->setTable("trx_orders");
        $this->setPermalink("trx_orders");
        $this->setPageTitle("Daftar Order");

        $this->setButtonEdit(true);
        $this->setButtonAdd(false);

        $this->addText("No Order","no_order")->strLimit(150)->maxLength(255);
        $this->addSelectTable("Nama Customer","customers_id",["table"=>"customers","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
        $this->addText("Tgl Mulai","tgl_mulai")
            ->indexDisplayTransform(function ($row) {
                return date('d M Y',strtotime($row));
            })->columnWidth(200);
        $this->addText("Type Package","id")
            ->indexDisplayTransform(function ($row) {
                $query = TrxOrders::findById($row);
                $type_package = $query->getPackagesId()->getTypePackage();
                if ($type_package == 'Regular'){
                    $btn = 'warning';
                }else{
                    $btn = 'success';
                }
                return "<span class='label label-".$btn." label-xs' style='text-transform: capitalize'>".$type_package."</span>";
            });
        $this->addSelectTable("Package","packages_id",["table"=>"packages","value_option"=>"id","display_option"=>"name","sql_condition"=>""])->columnWidth(200);
        $this->addText("Days Left","id")
            ->indexDisplayTransform(function ($row) {
                $order = TrxOrders::findById($row);
                if ($order->getStatusPayment() != 'Failed'){
                    $riwayat = TrxOrdersStatus::simpleQuery()
                        ->where('trx_orders_id',$row)
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
                        $return = '<label class="label label-warning">Paused</label>';
                    }else {
                        if ($d <= 0) {
                            $return = '<label class="label label-success">Finish</label>';
                        }else{
                            $return = '<input type="text" class="form-control custom-form" value="'.$d.' days" readonly>';
                        }
                    }
                }else{
                    $return = '<label class="label label-danger">Cancel</label>';
                }

                return $return;
            })->columnWidth(250);
        $this->addText("Days Used","id")
            ->indexDisplayTransform(function ($row) {
                $order = TrxOrders::findById($row);
                if ($order->getStatusPayment() != 'Failed'){
                    $riwayat = TrxOrdersStatus::simpleQuery()
                        ->where('trx_orders_id',$row)
                        ->orderBy('date','desc')
                        ->groupBy('date','trx_orders_id')
                        ->select('date','trx_orders_id')
                        ->get();
                    $ttl = 0;
                    foreach ($riwayat as $y){
                        $ttl += 1;
                    }
                    $return = '<input type="text" class="form-control custom-form" value="'.$ttl.' days" readonly>';
                }else{
                    $return = '<label class="label label-danger">Cancel</label>';
                }

                return $return;
            })->columnWidth(250);
        $this->addText("Sub Total","price")
            ->indexDisplayTransform(function ($row) {
                return "<span style='text-transform: capitalize'> Rp.".number_format($row)."</span>";
            });
        $this->addText("Discount","id")
            ->indexDisplayTransform(function ($row) {
                $discount = TrxOrdersRepository::getDiscount($row);
                return "<span style='text-transform: capitalize'>Rp.".$discount."</span>";
            });
        $this->addText("Kode Voucher","vouchers_code")
            ->indexDisplayTransform(function ($row) {
                if ($row == NULL) {
                    return "<span class='label label-danger label-xs' style='text-transform: capitalize'>Not Found</span>";
                }else{
                    return "<span class='label label-success label-xs' style='text-transform: capitalize'>".$row."</span>";
                }
            });
        $this->addText("Total","total")
            ->indexDisplayTransform(function ($row) {
                if ($row == NULL) {
                    return '-';
                }else{
                    return "<span style='text-transform: capitalize'> Rp.".number_format($row)."</span>";
                }
            });
        $this->addText("Metode Pembayaran","payment_method")
            ->indexDisplayTransform(function ($row) {
                if ($row == 'direct transfer'){
                    $btn = 'warning';
                }else{
                    $btn = 'success';
                }
                return "<span class='label label-".$btn." label-xs' style='text-transform: capitalize'>".$row."</span>";
            });
        $this->addText("Status Pembayaran","id")
            ->indexDisplayTransform(function ($row) {
                $order = TrxOrders::findById($row);
                $row = $order->getStatusPayment();
                $link = action('AdminTrxOrdersController@getUpdateStatus').'?id='.$order->getId();
                if($row == 'Waiting Payment') {
                    $btn = "<div class='btn-group'>
                <button type='button' class='btn btn-xs btn-warning dropdown-toggle' data-toggle='dropdown'>
                Waiting Payment <span class='caret'></span></button>
                <ul class='dropdown-menu' role='menu'>
                <li><a href='".$link."&status=Confirmation'><small>Confirmation</small></a></li>
                <li><a href='".$link."&status=Failed'><small>Cancel / Failed</small></a></li>
                </ul>
                </div>";
                }elseif ($row == 'Confirmation'){
                    $btn = "<div class='btn-group'>
                <button type='button' class='btn btn-xs btn-warning dropdown-toggle' data-toggle='dropdown'>
                Confirmation <span class='caret'></span></button>
                <ul class='dropdown-menu' role='menu'>
                <li><a href='".$link."&status=Success Payment'><small>Success Payment</small></a></li>
                <li><a href='".$link."&status=Failed'><small>Cancel / Failed</small></a></li>
                </ul>
                </div>";
                }elseif($row == 'Success Payment'){
                    $btn = "<button class='btn btn-xs btn-success'>Success Payment</button> : ".date('D, d M Y H:i',strtotime($order->getDatePayment()));
                }else{
                    $btn = "<button class='btn btn-xs btn-danger'>Cancel / Failed</button>";
                }
                return $btn;
            });
        $html = '<!-- Modal -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <div class="modal fade" id="tunda" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="titles">Tunda untuk besok</h4>
        </div>
        <div class="modal-body">
        <p>Anda yakin akan menunda langganan untuk tanggal <b><span id="tgl-tunda"></span></b></p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Tunda</button>
        </div>
        </div>

        </div>
        </div>';


        $this->setBeforeIndexTable($html);

        $this->javascript(function() {
            $url = action('AdminTrxOrdersController@getNextDay');
            return '
            $(".tunda").on("click",function () {
                id_order = $(this).attr("data-order");
                $("#tunda").modal("show");
                $.getJSON("'.$url.'?id="+id_order, function( data ) {

                    });
                    $("#tgl-tunda").html(id_order);
                    })	
                    ';
        });


        $this->hookIndexQuery(function($query) {
            // Todo: code query here

            // You can make query like laravel db builder
            if (g('status') == 'Incoming') {
                $query
                    ->whereIn('trx_orders.status_payment',['Waiting Payment','Confirmation']);
            }elseif (g('status') == 'On Going') {
                $query
                    ->where('must_end','>=',date('Y-m-d'))
                    ->where('trx_orders.status_payment','Success Payment');
            }elseif (g('status') == 'History'){
                $query
                    ->where(function ($query) {
                        $query->where('trx_orders.status_payment','Failed')
                            ->orWhere('must_end','<',date('Y-m-d'));
                    });
            }

            // Don't forget to return back
            return $query;
        });

        if (g('status')) {
            $this->setBeforeIndexTable(view("backend.orders.header_table")->render());
        }
        $this->addIndexActionButton("Import",url('admin/trx_orders/import'),"fa fa-upload","primary");

        $this->addIndexActionButton("Check Alergen",url('admin/trx_orders/alergen'),"fa fa-book","info");

        $this->hookSearchQuery(function(Builder $query) {
            $query->where("customers.name", "like", "%".g('q')."%")
                ->orwhere("trx_orders.no_order", "like", "%".g('q')."%")
                ->orwhere("packages.name", "like", "%".g('q')."%")
                ->orwhere("packages.type_package", "like", "%".g('q')."%")
                ->orwhere("vouchers_code", "like", "%".g('q')."%");
            return $query;
        });

    }
    public function getAdd(){
        $data['page_title'] = 'Add Order';
        $data['customer'] = Customers::all();
        $data['master_alergy'] = MasterAlergy::all();
        $data['carbon'] = MsCarbon::all();
        $data['protein'] = MsProtein::all();
        $data['driver'] = Drivers::all();
        return view('backend.orders.add',$data);
    }
    public function getEdit($id){
        $data['page_title'] = 'Edit Order';
        $data['customer'] = Customers::all();
        $data['master_alergy'] = MasterAlergy::all();
        $data['carbon'] = MsCarbon::all();
        $data['protein'] = MsProtein::all();
        $data['driver'] = Drivers::all();
        $order = TrxOrders::findById($id);
        $type = $order->getPackagesId()->getTypePackage();
        $data['list'] = PackagesRepository::findPackage($order->getCustomersId()->getId(),$order->getPeriode(),$type);
        $data['order'] = $order;
        $data['alergi'] = DB::table('trx_orders_alergy')
            ->where('trx_orders_id',$order->getId())
            ->get();
        $data['selected'] = TrxOrdersDate::simpleQuery()->where('trx_orders_id',$id)->get();

        return view('backend.orders.edit',$data);
    }

    public function getPrice(){
        $id_cus = g('id_customer');
        $periode = g('periode');
        $type = g('type_package');

        $package = PackagesRepository::findPackage($id_cus,$periode,$type);

        return response()->json($package);
    }

    public function postAddSave(){
        $date = explode(" :: ",g('date_start'));

        if (g('protein_from')){
            foreach (g('protein_from') as $row){
                $arr_p[] = array(
                    'protein'=>$row,
                );
            }
        }else{
            $arr_p = [];
        }

        if (g('carbo_from')){
            foreach (g('carbo_from') as $row){
                $arr_c[] = array(
                    'carbo'=>$row,
                );
            }
        }else{
            $arr_c = [];
        }

        if (g('day_for')) {
            $day_for = strtolower(implode(',',g('day_for')));
        }else{
            $day_for = NULL;
        }

        if (g('day_for_altf')) {
            $day_for_altf = strtolower(implode(',',g('day_for_altf')));
        }else{
            $day_for_altf = NULL;
        }

        $save = New TrxOrders();
        $save->setCreatedAt(date('Y-m-d H:i:s'));
        $save->setCustomersId(g('customer_id'));
        $save->setPeriode(g('periode'));
        $save->setPackagesId(g('packet'));
        if (g('vouchers_code') != NULL) {
            VouchersRepository::subQuota(g('vouchers_code'));
            $save->setVouchersCode(g('vouchers_code'));
        }
        $save->setPaymentMethod(g('metode_payment'));
        $save->setProtein(json_encode($arr_p));
        $save->setCarbo(json_encode($arr_c));
        $save->setProteinAlternative(g('protein_alternative'));
        $save->setCarboAlternative(g('carbo_alternative'));
        if (g('nama_alamat')) {
            if (AddressBookRepository::checkAddress(g('customer_id'),g('nama_alamat')) < 1) {
                $address1 = New AddressBook;
                $address1->setCustomersId(g('customer_id'));
                $address1->setName(g('nama_alamat'));
                $address1->setAddress(g('alamat'));
                $address1->setLatitude(g('latitude'));
                $address1->setLongitude(g('longitude'));
                $address1->setReceiver(NULL);
                $address1->setDriversId(g('driver_id'));
                $address1->setDetailAddress(g('detail_address'));
                $address1->setNoPenerima(NULL);
                $address1->setCatatan(g('catatan'));
                $address1->save();
            }
        }
        $save->setAddressBookId(g('nama_alamat'));
        $save->setDriversId(g('driver_id'));
        $save->setAddress(g('alamat'));
        $save->setDetailAddress(g('detail_address'));
        $save->setLatitude(g('latitude'));
        $save->setLongitude(g('longitude'));
        $save->setCatatan(g('catatan'));
        $save->setNamaPenerima(NULL);
        $save->setNoPenerima(NULL);
        if (g('nama_alamat_second')) {
            if (AddressBookRepository::checkAddress(g('customer_id'),g('nama_alamat_second')) < 1) {
                $address1 = New AddressBook;
                $address1->setCustomersId(g('customer_id'));
                $address1->setName(g('nama_alamat_second'));
                $address1->setAddress(g('alamat_second'));
                $address1->setLatitude(g('latitude_second'));
                $address1->setLongitude(g('longitude_second'));
                $address1->setReceiver(NULL);
                $address1->setDriversId(g('driver_id_second'));
                $address1->setDetailAddress(g('detail_address_second'));
                $address1->setNoPenerima(NULL);
                $save->setCatatan(g('catatan'));
                $address1->save();
            }
        }
        $save->setAddressNameSecond(g('nama_alamat_second'));
        $save->setDriversIdSecond(g('driver_id_second'));
        $save->setAddressSecond(g('alamat_second'));
        $save->setDetailAddressSecond(g('detail_address_second'));
        $save->setLatitudeSecond(g('latitude_second'));
        $save->setLongitudeSecond(g('longitude_second'));
        $save->setCatatanAltf(g('catatan_second'));
        $save->setNamaPenerimaSecond(NULL);
        $save->setNoPenerimaSecond(NULL);
        $save->setStatusPayment('Waiting Payment');
        $save->setStatusBerlangganan('Pending');
        $save->setNoOrder('EZFIT'.time());
        $save->setPrice(g('price'));

        $save->setDayFor($day_for);
        $save->setDayForAltf($day_for_altf);

        if (g('total_diskon')) {
            $save->setTotal(g('total_diskon'));
        }else{
            $save->setPrice(g('price'));
            $save->setTotal(g('price'));
        }
        $save->save();

        foreach ($date as $row){
            $simpan[] = array(
                'trx_orders_id' => $save->getId(),
                'date'=>$row,
            );
        }
        if ($simpan){
            DB::table('trx_orders_date')->insert($simpan);
        }
        $start = TrxOrdersDate::simpleQuery()->orderBy('date','asc')->where('trx_orders_id',$save->getId())->first();
        $end = TrxOrdersDate::simpleQuery()->orderBy('date','desc')->where('trx_orders_id',$save->getId())->first();

        $up = TrxOrders::findById($save->getId());
        $up->setTglMulai($start->date);
        $up->setMustEnd($end->date);
        $up->save();

//        alergy
        $alrg = g('alergy');
        if(!empty($alrg)){
            foreach ($alrg as $row){
                $arr_er[] = array(
                    'trx_orders_id' => $save->getId(),
                    'master_alergy_id' => $row,
                );
            }
            DB::table('trx_orders_alergy')->insert($arr_er);
        }

        if(g('submit') == 'save'){
            return cb()->redirect(action("AdminTrxOrdersController@getIndex").'?status='.g('status_url'),'Success submit menu',"success");
        }else{
            return redirect()->back()->with(["message_type"=>'success','message'=>'Success submit menu'])->withInput();
        }
    }
    public function postEditSave($id){
        $date = explode(" :: ",g('date_start'));

        $lis = TrxOrdersDate::simpleQuery()->where('trx_orders_id',$id)
            ->get();

        foreach ($lis as $l){
            if (!in_array($l,$date)){
                DB::table('trx_orders_date')->where('id',$l->id)->delete();
            }
        }

        foreach ($date as $ld){
            $cd = TrxOrdersDate::simpleQuery()->where('date',$ld)
                ->where('trx_orders_id',$id)
                ->count();
            if ($cd == 0){
                $sav = New TrxOrdersDate();
                $sav->setTrxOrdersId($id);
                $sav->setDate($ld);
                $sav->save();
            }
        }

        if (g('protein_from')){
            foreach (g('protein_from') as $row){
                $arr_p[] = array(
                    'protein'=>$row,
                );
            }
        }else{
            $arr_p = [];
        }

        if (g('carbo_from')){
            foreach (g('carbo_from') as $row){
                $arr_c[] = array(
                    'carbo'=>$row,
                );
            }
        }else{
            $arr_c = [];
        }

        if (g('day_for')) {
            $day_for = strtolower(implode(',',g('day_for')));
        }else{
            $day_for = NULL;
        }

        if (g('day_for_altf')) {
            $day_for_altf = strtolower(implode(',',g('day_for_altf')));
        }else{
            $day_for_altf = NULL;
        }

        $save = TrxOrders::findById($id);


        $save->setCreatedAt(date('Y-m-d H:i:s'));
        $save->setPeriode(g('periode'));
        $save->setPackagesId(g('packet'));
        if (g('vouchers_code') != NULL) {
            VouchersRepository::subQuota(g('vouchers_code'));
            $save->setVouchersCode(g('vouchers_code'));
        }
        $save->setPaymentMethod(g('metode_payment'));
        $save->setProtein(json_encode($arr_p));
        $save->setCarbo(json_encode($arr_c));
        $save->setProteinAlternative(g('protein_alternative'));
        $save->setCarboAlternative(g('carbo_alternative'));
        if (g('nama_alamat')) {
            if (AddressBookRepository::checkAddress(g('customer_id'),g('nama_alamat')) < 1) {
                $address1 = New AddressBook;
                $address1->setCustomersId(g('customer_id'));
                $address1->setName(g('nama_alamat'));
                $address1->setAddress(g('alamat'));
                $address1->setLatitude(g('latitude'));
                $address1->setLongitude(g('longitude'));
                $address1->setReceiver(NULL);
                $address1->setDriversId(g('driver_id'));
                $address1->setDetailAddress(g('detail_address'));
                $address1->setNoPenerima(NULL);
                $address1->setCatatan(g('catatan'));
                $address1->save();
            }
        }
        $save->setAddressBookId(g('nama_alamat'));
        $save->setDriversId(g('driver_id'));
        $save->setAddress(g('alamat'));
        $save->setDetailAddress(g('detail_address'));
        $save->setLatitude(g('latitude'));
        $save->setLongitude(g('longitude'));
        $save->setCatatan(g('catatan'));
        $save->setNamaPenerima(NULL);
        $save->setNoPenerima(NULL);
        if (g('nama_alamat_second')) {
            if (AddressBookRepository::checkAddress(g('customer_id'),g('nama_alamat_second')) < 1) {
                $address1 = New AddressBook;
                $address1->setCustomersId(g('customer_id'));
                $address1->setName(g('nama_alamat_second'));
                $address1->setAddress(g('alamat_second'));
                $address1->setLatitude(g('latitude_second'));
                $address1->setLongitude(g('longitude_second'));
                $address1->setReceiver(NULL);
                $address1->setDriversId(g('driver_id_second'));
                $address1->setDetailAddress(g('detail_address_second'));
                $address1->setNoPenerima(NULL);
                $save->setCatatan(g('catatan'));
                $address1->save();
            }
        }
        $save->setAddressNameSecond(g('nama_alamat_second'));
        $save->setDriversIdSecond(g('driver_id_second'));
        $save->setAddressSecond(g('alamat_second'));
        $save->setDetailAddressSecond(g('detail_address_second'));
        $save->setLatitudeSecond(g('latitude_second'));
        $save->setLongitudeSecond(g('longitude_second'));
        $save->setCatatanAltf(g('catatan_second'));
        $save->setNamaPenerimaSecond(NULL);
        $save->setNoPenerimaSecond(NULL);
        $save->setNoOrder('EZFIT'.time());
        $save->setPrice(g('price'));

        $save->setDayFor($day_for);
        $save->setDayForAltf($day_for_altf);

        if (g('total_diskon')) {
            $save->setTotal(g('total_diskon'));
        }else{
            $save->setPrice(g('price'));
            $save->setTotal(g('price'));
        }
        $save->save();

        $change['customers_id'] = g('customer_id');
        DB::table('trx_orders')->where('id',$id)->update($change);

        if(g('submit') == 'save'){
            return cb()->redirect(action("AdminTrxOrdersController@getIndex")."?status=".g("status_url"),'Success submit menu',"success");
        }else{
            return redirect()->back()->with(["message_type"=>'success','message'=>'Success submit menu'])->withInput();
        }
    }
    public function getDetail($id){
        $data['page_title'] = 'Detail Order';
        $row = DB::table('trx_orders')
            ->select('trx_orders.*','')
            ->leftjoin('customers','customers.id','trx_orders.customers_id')
            ->leftjoin('packages','packages.id','trx_orders.packages_id')
            ->select(
                'trx_orders.*',
                'customers.name as c_name',
                'packages.name as p_name',
                'customers.ho_hp as ho_hp',
                'packages.category as category',
                'packages.price_u1 as price_u1',
                'packages.price_u2 as price_u2',
                'packages.price_u3 as price_u3',
                'packages.price_m1 as price_m1',
                'packages.price_m2 as price_m2',
                'packages.price_m3 as price_m3',
                'packages.type_package as package_type'
            )
            ->where('trx_orders.id',$id)
            ->first();

        $data['driver_first'] = TrxOrdersRepository::getDriverName($row->drivers_id);
        $data['driver_second'] = TrxOrdersRepository::getDriverName($row->drivers_id_second);

        $history = TrxOrdersStatus::simpleQuery()
            ->where('trx_orders_id',$id)
            ->orderBy('date','desc')
            ->groupBy('date','trx_orders_id')
            ->select('date','trx_orders_id')
            ->get();

        foreach ($history as $key => $h) {
            $h->status = TrxOrdersRepository::getStatusPengiriman($h->trx_orders_id,$h->date);
        }

        $data['all_driver'] = Drivers::all();
        $data['pengiriman'] = TrxOrdersDate::simpleQuery()
            ->where('trx_orders_id',$id)
            ->orderBy('date','desc')
            ->get();
        $data['history'] = $history;

        $data['data_paused'] = TrxOrdersPauseDate::simpleQuery()
            ->where('trx_orders_id',$id)
            ->select('date')
            ->groupBy('date')
            ->orderBy('date','desc')
            ->get();

        foreach ($data['data_paused'] as $key => $p) {
            $p->date = Carbon::parse($p->date)->format('d F Y');
        }

        $data['all_day'] = 'senin, selasa, rabu, kamis, jumat, sabtu, ';

        $alergen = DB::table('trx_orders_alergy')
            ->select('master_alergy.name as name')
            ->join('master_alergy','master_alergy.id','=','trx_orders_alergy.master_alergy_id')
            ->where('trx_orders_id',$id)
            ->get();
        $data['row'] = $row;
        $data['alergen'] = $alergen;
        return view('backend.orders.detail',$data);
    }

    public function getListAddress(){
        $list = AddressBookRepository::findAddress(g('id'));

        $array = [];
        foreach ($list as $row){
            $array[] = array(
                'name' => $row->name,
            );
        }
        return response()->json($array);
    }
    public function getAddress(){
        $name = g('name');
        $id = g('id');


        return response()->json(CustomersRepository::findAddress($id,$name));
    }

    public function getDelete($id){
        TrxOrdersRepository::deleteRelation($id);
        return redirect()->back()->with(["message_type"=>'success','message'=>'Success delete Order']);
    }
    public function getNextDay(){
        $id = g('id');
        $check = TrxOrders::findById($id);
        $date = dateRange( $check->getTglMulai(),$check->getMustEnd());
        if ($check->getDayOff()){
            $off = json_decode($check->getDayOff(),true);
            foreach ($off as $y){
                $off_d[] = $y['day_off'];
            }
        }else{
            $off_d = [];
        }
        foreach ($date as $y){
            $d = HariApa($y);
            if($d != 'Minggu'){
                if (!in_array($d,$off_d)){
                    echo $y.'='.HariApa($y).'<br>';
                }

            }
        }
        exit();

    }
    public function getUpdateStatus(){
        $id = g('id');
        $status = g('status');

        $up = TrxOrders::findById($id);
        $up->setStatusPayment($status);
        if ($status == 'Success Payment'){
            $up->setDatePayment(date('Y-m-d H:i:s'));
        }elseif ($status == 'Failed') {
            if ($up->getVouchersCode() != NULL) {
                VouchersRepository::addQuota($up->getVouchersCode());
            }
        }elseif($status == 'Confirmation'){
            $up->setDatePayment(date('Y-m-d H:i:s'));
        }
        $up->save();

        if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
            $dates = getMustEnd(date('Y-m-d'),1);
        }else{
            $dates = date('Y-m-d');
        }
        $ds = HariApa($dates);
        if ($ds == 'minggu'){
            $dates = getMustEnd($dates,1);
        }else{
            $dates = $dates;
        }
        $site = asset('');
        $row = TrxOrders::simpleQuery()
            ->where('trx_orders.id',$id)
            ->leftJoin('drivers','drivers.id','=','trx_orders.drivers_id')
            ->leftJoin('packages','packages.id','=','trx_orders.packages_id')
            ->select('trx_orders.*',
                'drivers.name as driver_name',
                'packages.name as package_name',
                DB::raw("concat('$site',packages.photo) as photo")
            );
        $row = $row->first();

        $alergy = TrxOrdersAlergy::simpleQuery()
            ->join('master_alergy','master_alergy.id','=','trx_orders_alergy.master_alergy_id')
            ->select('name',DB::raw("concat('$site',master_alergy.photo) as photo"))
            ->where('trx_orders_id',$row->id)
            ->get();

        $d = dateDiff($row->must_end,$row->tgl_mulai,$row->day_off);
        if ($d <= 0){
            $row->day_left = 'Expired';
        }else{
            $row->day_left = $d;
        }
        $d = '';
        if (!empty($day_off)){
            foreach ($day_off as $l){
                $d .= strtolower($l['day_off']).',';
            }
        }
        $date = dateRange($row->tgl_mulai,$row->must_end);
        $off_d = [];
        if ($row->day_off){
            $off = json_decode($row->day_off,true);
            if (!empty($off)){
                foreach ($off as $y){
                    $off_d[] = $y['day_off'];
                }
            }
        }

        $row->day_off = rtrim($d, ", ");
        $row->day_for_second = $row->day_for_altf;
        $row->catatan_second = $row->catatan_altf;
        $row->alergy = $alergy;
        $date_before = NULL;
        $date_next = NULL;
        if(!empty($date) && $row->status_payment != 'Failed'){
            $inow = 1;
            foreach ($date as $y){
                $d = HariApa($y);
                $d = strtolower($d);
                if($d != 'minggu'){
                    if (!in_array($d,$off_d)){
                        if ($dates <= $y){
                            $date_next[] = array(
                                'date' => $y,
                            );
                        }
                    }
                }
            }
        }
        $find_riwayat = TrxOrdersStatus::simpleQuery()
            ->where('trx_orders_id',$row->id)
            ->groupBy('date')
            ->whereNotNull('date')
            ->select('date')
            ->orderBy('date','asc')
            ->get();
        $ttl = 0;
        foreach ($find_riwayat as $y){
            $ttl += 1;
        }
        $d = $row->periode - $ttl;
        $row->sisa_hari = $d;
        $row->day_left = $d;
        $row->date_before = $find_riwayat;
        $row->date_next = $date_next;

        $mess['type'] = 'order';
        $mess['id_order'] = $id;
        if (g('status') == 'Success Payment'){
            $content = 'Pembayaran kamu sudah disetujui, langganan kamu sudah aktif.';
        }elseif(g('status') == 'Confirmation'){
            $content = 'Pembayaran kamu sudah konfirmasi, tunggu hingga pembayaran anda telah disetujui';
        }else{
            $content = 'Pembayaran kamu telah ditolak, langganan kamu tidak akfif.';
        }

        $mess['content'] = $content;
        $mess['type_notice'] = 'ongoing';
        $data['title'] = 'Status Pembayaran';
        $data['content'] = $content;
        $data['data'] = $mess;

        $regid_a[] = $up->getCustomersId()->getRegid();
        $regid_i[] = $up->getCustomersId()->getRegidIos();
        $logs[] = SendFcm($regid_a,$data,'IOS');
        $logs[] = SendFcm($regid_i,$data,'IOS');

        $log = new LogNotice();
        $log->setCustomersId($up->getCustomersId()->getId());
        $log->setTrxOrdersId($up->getId());
        $log->setContent($content);
        $log->setTitle('Status Pembayaran');
        $log->setType('order');
        $log->setTypeNotice('ongoing');
        $log->setCreatedAt(date('Y-m-d H:i:s'));
        $log->save();

        return redirect()->back()->with(["message_type"=>'success','message'=>'Success update Order status']);
    }

    public function getVouchers(){
        $code = g('vouchers_code');
        $nominal = g('nominal');
        $customer = g('customer_id');

        $check = Vouchers::simpleQuery()
            ->where('code',$code)
            ->whereDate('date_start', '<=', date("Y-m-d"))
            ->whereDate('date_end', '>=', date("Y-m-d"))
            ->first();

        $check_user = Customers::findById($customer);

        $not_available = TrxOrders::simpleQuery()
            ->where('customers_id',$customer)
            ->where('vouchers_code',$code)
            ->first();

        if ($check->type_voucher != 'semua'){
            if ($check->type_voucher != $check_user->getTypeCustomer()){
                $result['ajax_status'] = 0;
                $result['ajax_message'] = 'Vouchers Cannot Be Used!';
                return response()->json($result);
                exit();
            }
        }

        if (!$check) {
            $result['ajax_status'] = 0;
            $result['ajax_message'] = 'Voucher Not Found!';
        }elseif($not_available){
            $result['ajax_status'] = 0;
            $result['ajax_message'] = 'Vouchers Cannot Be Used!';
        }else{
            $result['ajax_status'] = 1;
            $result['ajax_message'] = 'Success';
            $result['sub_total'] = 'Rp.'.number_format($nominal);
            $discount = VouchersRepository::getDiscountByCode($code,$nominal);
            $result['discount'] = 'Rp.'.number_format($discount);
            $total = $nominal - $discount;
            $result['total'] = 'Rp.'.number_format($total);
            $result['total_real'] = $total;
        }

        return response()->json($result);
    }

    public function getUpdateTotalAll(){
        $row = DB::table('trx_orders')->get();
        foreach ($row as $key => $value) {
            DB::table('trx_orders')->where('id',$value->id)->update(['total' => $value->price]);
        }

        return 'done';
    }
    public function getImport(){
        $data['page_title'] = 'Import Order';

        return view('backend.orders.import',$data);
    }
    public function postImport(){
        $array = Excel::toArray(new OrderCustomer, request()->file('file'));
        $data = $array[0];


        foreach ($data as $row){
            if ($row[5] == 'Reguler'){
                $type = 'Regular';
                $name = $row[6];
            }elseif ($row[5] == 'ProPack'){
                $type = 'Propack';
                $name = $row[6]. ' (PP)';
            }else{
                $type = $row[5];
                $name = $row[6];
            }
            $customer = CustomersRepository::findDetail($row[3]);
            $peride = str_replace(" Days","",$row[7]);
            $package = PackagesRepository::findPack($type,$name);
            $driver = Drivers::findBy('name',$row[25]);
            if ($row[4] == 'Umum'){
                if ($peride == 6){
                    $price = $package->getPriceU2();
                }else{
                    $price = $package->getPriceU3();
                }
            }else{
                if ($peride == 6){
                    $price = $package->getPriceM2();
                }else{
                    $price = $package->getPriceM3();
                }
            }
            $offD = [];
            if ($row[9] != 'x'){
                $off = explode(',',strtolower($row[9]));
                foreach ($off  as $l){
                    $offD[] = array(
                        'day_off' => $l
                    );
                }
            }
            $protein = [];
            if($row[11] != 'x'){
                $pro = explode(',',strtolower($row[11]));
                foreach ($pro  as $l){
                    $protein[] = array(
                        'protein' => $l
                    );
                }
            }
            if ($row[12] != 'x'){
                $pro_altf = $row[12];
            }else{
                $pro_altf = NULL;
            }
            $carbo = [];
            if($row[13] != 'x'){
                $car = explode(',',strtolower($row[13]));
                foreach ($car  as $l){
                    $carbo[] = array(
                        'carbo' => $l
                    );
                }
            }
            if ($row[14] != 'x'){
                $car_altf = $row[14];
            }else{
                $car_altf = NULL;
            }
            $end = getRealEnd($row[8],(int)$peride,json_encode($offD));
            if (!empty($package->getId())){
                $for_day = str_replace(", ",",",$row[19]);
                $save[] = array(
                    'no_order'=> 'EZFIT'.time(),
                    'customers_id' => $customer->getId(),
                    'periode' => (int)$peride,
                    'packages_id' => $package->getId(),
                    'tgl_mulai' => $row[8],
                    'day_off' => json_encode($offD),
                    'price' => $price,
                    'total' => $price,
                    'protein' => json_encode($protein),
                    'protein_alternative' => $pro_altf,
                    'carbo' => json_encode($carbo),
                    'carbo_alternative' => $car_altf,
                    'address_book_id' => $row[15],
                    'address' => $row[16],
                    'detail_address' => $row[17],
                    'catatan' => $row[18],
                    'payment_method' => 'direct transfer',
                    'status_payment' => 'Success Payment',
                    'drivers_id' => $driver->getId(),
                    'must_end'=>$end,
                    'status_berlangganan'=>'Pending',
                    'day_for' => strtolower($for_day),
                );
            }
        }
        if($save){
            $check = DB::table('trx_orders')->orderBy('id','desc')->first();
            DB::table('trx_orders')->insert($save);
            if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
                $dates = getMustEnd(date('Y-m-d'),1);
            }else{
                $dates = date('Y-m-d');
            }
            if ($check){
                $ids = $check->id;
            }else{
                $ids = 0;
            }
            $list = DB::table('trx_orders')
                ->where('id','>',$ids)
                ->get();

            foreach ($list as $row){
                $date = dateRange($row->tgl_mulai,$row->must_end);
                $off_d = [];
                if ($row->day_off){
                    $off = json_decode($row->day_off,true);
                    if (!empty($off)){
                        foreach ($off as $y){
                            $off_d[] = $y['day_off'];
                        }
                    }
                    foreach ($date as $y){
                        $d = HariApa($y);
                        $d = strtolower($d);
                        if($d != 'minggu'){
                            if (!in_array($d,$off_d)){
                                if ($dates >= $y){
                                    $date_next[] = array(
                                        'trx_orders_id'=> $row->id,
                                        'date' => $y,
                                        'status_pengiriman'=> 'Proses'
                                    );
                                }
                            }
                        }
                    }
                }
            }
            if ($date_next){
                DB::table('trx_orders_status')->insert($date_next);
            }
        }
        return redirect()->back()->with(["message_type"=>'success','message'=>'Success Confirmation!']);
    }
    public function getPengiriman(){
        if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
            $dates = getMustEnd(date('Y-m-d'),1);
        }else{
            $dates = date('Y-m-d');
        }
        $list = DB::table('trx_orders')
            ->where('id','>=',97)
            ->get();

        foreach ($list as $row){
            $date = dateRange($row->tgl_mulai,$row->must_end);
            $off_d = [];
            if ($row->day_off){
                $off = json_decode($row->day_off,true);
                if (!empty($off)){
                    foreach ($off as $y){
                        $off_d[] = $y['day_off'];
                    }
                }
                foreach ($date as $y){
                    $d = HariApa($y);
                    $d = strtolower($d);
                    if($d != 'minggu'){
                        if (!in_array($d,$off_d)){
                            if ($dates >= $y){
                                $date_next[] = array(
                                    'trx_orders_id'=> $row->id,
                                    'date' => $y,
                                    'status_pengiriman'=> 'Proses'
                                );
                            }
                        }
                    }
                }
            }
        }
        DB::table('trx_orders_status')->insert($date_next);
    }

    public function getArr(){
        $arr[] = 'EZFIT1577013744';
        $arr[] = 'EZFIT1577011437';
        $arr[] = 'EZFIT1577005747';
        $arr[] = 'EZFIT1577003185';
        $arr[] = 'EZFIT1576986235';
        $arr[] = 'EZFIT1576983805';
        $arr[] = 'EZFIT1576909927';
        $arr[] = 'EZFIT1576903770';
        $arr[] = 'EZFIT1576898906';
        $arr[] = 'EZFIT1576897872';
        $arr[] = 'EZFIT1576895377';
        $arr[] = 'EZFIT1576823018';
        $arr[] = 'EZFIT1576769872';
        $arr[] = 'EZFIT1576767013';
        $arr[] = 'EZFIT1576754497';
        $arr[] = 'EZFIT1576564698';
        $arr[] = 'EZFIT1576533912';
        $arr[] = 'EZFIT1576498230';
        $arr[] = 'EZFIT1575858534';
        $arr[] = 'EZFIT1575790449';
        $arr[] = 'EZFIT1575761746';
        $arr[] = 'EZFIT1575547124';
        $arr[] = 'EZFIT1575545461';
        $arr[] = 'EZFIT1575508337';
        $arr[] = 'EZFIT1575464831';

        if (g('type') == '1'){
            $update['is_paused'] = 1;
            DB::table('trx_orders')->wherein('no_order',$arr)->update($update);
        }else{
            $update['is_paused'] = NULL;
            DB::table('trx_orders')->where('no_order',$arr)->update($update);
        }
    }
    public function getDeletePengiriman(){
        $id_order = g('id');
        $date = g('date');

        $l = TrxOrdersStatus::simpleQuery()
            ->where('trx_orders_id',$id_order)
            ->where('date',$date)
            ->delete();

        return redirect()->back()->with(["message_type"=>'success','message'=>'Berhasil menghapus pengiriman !']);
    }
    public function postAddDate(){
        $id = g('id');
        $n = new TrxOrdersStatus();
        $n->setTrxOrdersId($id);
        $n->setStatusPengiriman('Proses');
        $n->setDate(g('date_start'));
        $n->save();

        return redirect()->back()->with(["message_type"=>'success','message'=>'Berhasil menambahkan pengiriman !']);
    }
    public function getSetMustEnd(){
        $list = DB::table('trx_orders')
            ->where('type_apps','Old')
            ->where('status_payment','Success Payment')
            ->whereNull('is_paused')
            ->get();

        foreach ($list as $row){
            $check = DB::table('trx_orders_date')
                ->where('trx_orders_id',$row->id)
                ->orderBy('date','desc')
                ->first();
            if (!empty($check)){
                $update['must_end'] = $check->date;
                DB::table('trx_orders')->where('id',$row->id)->update($update);
            }
        }
    }

    public function getAlergen(){
        $list['data'] = DB::table('master_alergy')
            ->get();

        return view('backend.orders.alergen',$list);
    }
    public function getEditMustEnd($id){
        $data['page_title'] = 'Edit Must End';
        $data['row'] = TrxOrders::findById($id);

        return view('backend.orders.must_end',$data);
    }
    public function postEditMustEnd($id){
        $find = TrxOrders::findById($id);
        $find->setMustEnd(g('must_end'));
        $find->save();

        return cb()->redirect(action("AdminTrxOrdersController@getIndex")."?status=".g("status_url"),'Success Edit Must End',"success");
    }

    public function postConfirmation(){
        $id = g('trx_id');
        $driver_id = g('driver_id');
        $driver_id_second = g('driver_id_second');

        $save = TrxOrders::findById($id);
        $save->setStatusPayment('Success Payment');
        $save->setDriversId($driver_id);
        $save->setDriversIdSecond($driver_id_second);
        $save->save();

        $content = 'Pembayaran kamu sudah disetujui, langganan kamu sudah aktif.';

        $mess['content'] = $content;
        $mess['type_notice'] = 'ongoing';
        $data['title'] = 'Status Pembayaran';
        $data['content'] = $content;
        $data['data'] = $mess;

        $regid_a[] = $save->getCustomersId()->getRegid();
        $regid_i[] = $save->getCustomersId()->getRegidIos();
        $logs[] = SendFcm($regid_a,$data,'IOS');
        $logs[] = SendFcm($regid_i,$data,'IOS');

        $log = new LogNotice();
        $log->setCustomersId($save->getCustomersId()->getId());
        $log->setTrxOrdersId($save->getId());
        $log->setContent($content);
        $log->setCreatedAt(date('Y-m-d H:i:s'));
        $log->save();

        return redirect()->back()->with(["message_type"=>'success','message'=>'Success Confirmation!']);
    }

    public function getListDate(){
        ini_set('max_execution_time', '0');
        $list = TrxOrders::simpleQuery()
            ->where('type_apps','Old')
            ->whereNull('day_off')
            ->where('status_payment','Success Payment')
            ->whereNull('is_paused')
            ->get();
        $save = [];
        foreach ($list as $row){
            $l = TrxOrdersStatus::simpleQuery()
                ->where('trx_orders_id',$row->id)
                ->select('date','trx_orders_id')
                ->groupBy('date','trx_orders_id')
                ->get();
            $ttl = 0;
            foreach ($l as $r){
                $ttl += 1;
            }
            $periode = $row->periode;
            $to_date = getMustEnd($row->tgl_mulai,150);
            $from_date = $row->tgl_mulai;
            $now = 0;
            while(strtotime($from_date) <= strtotime($to_date)){
                $libs = TrxOrdersPauseDate::simpleQuery()
                    ->where('trx_orders_id',$row->id)
                    ->select('date','trx_orders_id')
                    ->groupBy('date','trx_orders_id')
                    ->where('date',$from_date)
                    ->get();
                $libur = 'date("N",strtotime($from_date))!=7';
                foreach ($libs as $s){
                    $libur .= '&& $from_date != $s->date';
                }
                if(eval("return $libur;")) {
                    $now = $now + 1;
                    if ($now <= $periode){
                        $save[] = array(
                            'trx_orders_id' => $row->id,
                            'date'=>$from_date,
                            'created_at' => date('Y-m-d H:i:s'),
                        );
                    }
                }
                $from_date = date ("Y-m-d", strtotime("+1 day", strtotime($from_date)));
            }
        }
        DB::table('trx_orders_date')->insert($save);
        dd($list);
    }

    public function getListDateLibur(){
        ini_set('max_execution_time', '0');
        $list = TrxOrders::simpleQuery()
            ->whereNotNull('day_off')
            ->whereNull('is_paused')
            ->where('type_apps','Old')
            ->where('status_payment','Success Payment')
            ->get();
        $save = [];
        foreach ($list as $row){

            $for = json_decode($row->day_off,true);
            $snn = 0;
            $sel = 0;
            $rab = 0;
            $kam = 0;
            $jum = 0;
            $sab = 0;
            $mng = 0;

            if (!empty($for)){
                foreach ($for as $a){
                    if ($a['day_off'] == 'senin'){
                        $snn = 1;
                    }
                    if ($a['day_off'] == 'selasa'){
                        $sel = 1;
                    }
                    if ($a['day_off'] == 'rabu'){
                        $rab = 1;
                    }
                    if ($a['day_off'] == 'kamis'){
                        $kam = 1;
                    }
                    if ($a['day_off'] == 'jumat'){
                        $jum = 1;
                    }
                    if ($a['day_off'] == 'sabtu'){
                        $sab = 1;
                    }
                    $mng = 1;
                }
            }

            $l = TrxOrdersStatus::simpleQuery()
                ->where('trx_orders_id',$row->id)
                ->select('date')
                ->groupBy('date')
                ->get();
            $ttl = 0;
            foreach ($l as $r){
                $ttl += 1;
            }
            $periode = $row->periode;
            $to_date = getMustEnd($row->tgl_mulai,100);
            $from_date = $row->tgl_mulai;
            $now = 0;
            while(strtotime($from_date) <= strtotime($to_date)){
                $libs = TrxOrdersPauseDate::simpleQuery()
                    ->where('trx_orders_id',$row->id)
                    ->select('date','trx_orders_id')
                    ->groupBy('date','trx_orders_id')
                    ->where('date',$from_date)
                    ->get();
                $libur = 'date("N",strtotime($from_date))!=7';

                if($snn == 1){
                    $libur .= '&& date("N",strtotime($from_date))!=1';
                }
                if($sel == 1){
                    $libur .= '&& date("N",strtotime($from_date))!=2';
                }
                if($rab == 1){
                    $libur .= '&& date("N",strtotime($from_date))!=3';
                }
                if($kam == 1){
                    $libur .= '&& date("N",strtotime($from_date))!=4';
                }
                if($jum == 1){
                    $libur .= '&& date("N",strtotime($from_date))!=5';
                }

                if($sab == 1){
                    $libur .= '&& date("N",strtotime($from_date))!=6';
                }

                foreach ($libs as $s){
                    $libur .= '&& $from_date != $s->date';
                }


                if(eval("return $libur;")) {
                    $now = $now + 1;
                    if ($now <= $periode){
                        $save[] = array(
                            'trx_orders_id' => $row->id,
                            'date'=>$from_date,
                            'created_at' => date('Y-m-d H:i:s'),
                        );
                    }
                }

                $from_date = date ("Y-m-d", strtotime("+1 day", strtotime($from_date)));
            }
        }
        DB::table('trx_orders_date')->insert($save);
        dd($list);
    }
    public function getIndex(){
//        g('status') == 'Incoming'
//        g('status') == 'On Going'
        $data['page_title'] = 'List Order';

        return view('backend.orders.index',$data);
    }
    public function getJson($status){
        if (g('start')){
            $start = g('start');
        }else{
            $start = 0;
        }
        if (g('length')){
            $lengh = g('length');
        }else{
            $lengh = 10;
        }
        $list = TrxOrders::simpleQuery()
            ->join('packages','packages.id','=','trx_orders.packages_id')
            ->join('customers','customers.id','=','trx_orders.customers_id')
            ->select('trx_orders.*',
                'customers.name as customer_name',
                'packages.type_package as type_package',
                'packages.name as package_name'
            );
        if (!empty(g('order')[0]['column'])){
            $type_order = g('order')[0]['dir'];
            if (g('order')[0]['column'] == 2){
                $list = $list->orderBy('no_order',$type_order);
            }elseif (g('order')[0]['column'] == 3){
                $list = $list->orderBy('customers.name',$type_order);
            }elseif (g('order')[0]['column'] == 4){
                $list = $list->orderBy('trx_orders.tgl_mulai',$type_order);
            }elseif (g('order')[0]['column'] == 5){
                $list = $list->orderBy('packages.type_package',$type_order);
            }elseif (g('order')[0]['column'] == 6){
                $list = $list->orderBy('packages.name',$type_order);
            }elseif (g('order')[0]['column'] == 9){
                $list = $list->orderBy('trx_orders.price',$type_order);
            }else{
                $list = $list->orderBy('trx_orders.id',$type_order);
            }
        }
        if (!empty(g('search')['value'])){
            $list = $list->where(function ($query) {
                $query->where('trx_orders.no_order','like','%'.g('search')['value'].'%')
                    ->orwhere('customers.name','like','%'.g('search')['value'].'%');
            });
        }
        if ($status == 'Incoming'){
            $list = $list->whereIn('trx_orders.status_payment',['Waiting Payment','Confirmation']);
        }elseif($status == 'On Going'){
            $list = $list->where(function ($query) {
                $query->where('must_end','>=',date('Y-m-d'))
                    ->where('trx_orders.status_payment','Success Payment')
                    ->orWhere('is_paused',1);
            });
        }elseif($status == 'History'){
            $list = $list->where(function ($query) {
                $query->where('trx_orders.status_payment','Failed')
                    ->whereNull('is_paused')
                    ->orWhere('must_end','<',date('Y-m-d'));
            });
        }
        $ttl_record = $list->get();
        $list = $list->skip($start)->take($lengh)->get();
        $data['draw'] = g('draw')+1;
        $data['recordsTotal'] = count($ttl_record);
        $data['recordsFiltered'] = count($ttl_record);
        $data['data'] = [];
        foreach ($list as $row) {
            $find_riwayat = TrxOrdersDate::simpleQuery()
                ->where('trx_orders_id',$row->id)
                ->select('date')
                ->get();
            $ttl = 0;
            foreach ($find_riwayat as $y) {
                $cheking = TrxOrdersStatus::simpleQuery()
                    ->where('trx_orders_id', $row->id)
                    ->where('date', $y->date)
                    ->count();
                if($cheking > 0) {
                    $ttl += 1;
                }
            }

            $price = $row->price;
            $total = $row->total;
            $discount = $total - $price;
            if ($row->vouchers_code == NULL) {
                $label = "<span class='label label-danger label-xs' style='text-transform: capitalize'>Not Found</span>";
            }else{
                $label = "<span class='label label-success label-xs' style='text-transform: capitalize'>".$row->vouchers_code."</span>";
            }
            if ($row->type_package == 'Regular'){
                $btn = 'warning';
            }else{
                $btn = 'success';
            }
            if ($row->status_payment == 'Failed'){
                $sisa = "<span class='label label-danger label-xs' style='text-transform: capitalize'>Cancel / Failed</span>";
            }else{
                if($row->periode - $ttl == 0){
                    $sisa = "<span class='label label-primary label-xs' style='text-transform: capitalize'>Finish</span>";
                }else{
                    $sisa = $row->periode - $ttl;
                }
            }
            if ($row->is_paused == 1){
                $sisa = "<span class='label label-warning label-xs' style='text-transform: capitalize'>Paused</span>";
            }
            $link = action('AdminTrxOrdersController@getUpdateStatus').'?id='.$row->id;
            if($row->status_payment == 'Waiting Payment') {
                $btns = "<div class='btn-group'>
                        <button type='button' class='btn btn-xs btn-warning dropdown-toggle' data-toggle='dropdown'>
                        Waiting Payment <span class='caret'></span></button>
                        <ul class='dropdown-menu' role='menu'>
                        <li><a href='".$link."&status=Confirmation'><small>Confirmation</small></a></li>
                        <li><a href='".$link."&status=Failed'><small>Cancel / Failed</small></a></li>
                        </ul>
                        </div>";
            }elseif ($row->status_payment == 'Confirmation'){
                $btns = "<div class='btn-group'>
                        <button type='button' class='btn btn-xs btn-warning dropdown-toggle' data-toggle='dropdown'>
                        Confirmation <span class='caret'></span></button>
                        <ul class='dropdown-menu' role='menu'>
                        <li><a href='".$link."&status=Success Payment'><small>Success Payment</small></a></li>
                        <li><a href='".$link."&status=Failed'><small>Cancel / Failed</small></a></li>
                        </ul>
                        </div>";
            }elseif($row->status_payment == 'Success Payment'){
                $btns = "<button class='btn btn-xs btn-success'>Success Payment</button> : ".date('D, d M Y H:i',strtotime($row->date_payment));
            }else{
                $btns = "<button class='btn btn-xs btn-danger'>Cancel / Failed</button>";
            }
            $package =  "<span class='label label-".$btn." label-xs' style='text-transform: capitalize'>".$row->type_package."</span>";
            $data['data'][] = [
                '',
                '
                    <a href="'.url('admin/trx_orders/detail/'.$row->id).'?status='.$status.'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a> 
                    <a href="'.url('admin/trx_orders/edit/'.$row->id).'?status='.$status.'" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
                    <a href="javascript:;" class="btn btn-danger btn-xs" onclick="deleteConfirmation(`'.url("admin/trx_orders/delete").'/'.$row->id.'`)"><i class="fa fa-trash"></i></a>
                ',
                $row->no_order,
                $row->customer_name,
                date('Y/m/d',strtotime($row->tgl_mulai)),
                $package,
                $row->package_name,
                $sisa,
                $ttl,
                'Rp '.number_format($row->price),
                'Rp '.number_format($discount),
                $label,
                'Rp '.number_format($row->total),
                $row->payment_method,
                $btns,
            ];
        }
        $res = response()->json($data);
        $res->send();
        exit;
    }
    function getEnd(){
        ini_set('max_execution_time', '0');
        $list =  TrxOrdersDate::simpleQuery()
            ->join('trx_orders','trx_orders.id','=','trx_orders_date.trx_orders_id')
            ->where(function ($query) {
                $query->where('trx_orders.status_payment','Failed')
                    ->whereNull('is_paused')
                    ->where('id',127);
            })
            ->select('trx_orders.id as trx_orders_id','trx_orders_date.*')
            ->get();

        foreach ($list as $l){
            $check = DB::table('trx_orders_status')
                ->where('trx_orders_id',$l->trx_orders_id)
                ->where('date',$l->date)->count();
            if ($check == 0){
                $save[] = array(
                    'trx_orders_id' => $l->trx_orders_id,
                    'date' => $l->date,
                    'status_pengiriman' => 'Proses'
                );
            }
        }

        DB::table('trx_orders_status')->insert($save);
    }

    public function doLoop($now){
        $tgl = $now;
        $date = date('D',strtotime($now));
        if($date == 'Sun'){
            $tgl = date('Y-m-d',strtotime($now ."+1 day"));
            return self::doLoop($tgl);
        }else{
            $result = $tgl;
        }
        return $result;
    }

    public function getTest(){

        $list = TrxOrdersDate::simpleQuery()
            ->where('date','2020-06-01')
            ->get();


        foreach ($list as $y){
            $last = TrxOrdersDate::simpleQuery()
                ->where('trx_orders_id',$y->trx_orders_id)
                ->orderBy('date','desc')
                ->first();
            $tmb = self::doLoop(date('Y-m-d',strtotime($last->date ."+1 day")));
            $delete[] = $y->id;
            $arr[] = array(
                'trx_orders_id' => $last->trx_orders_id,
                'date' => $tmb,
                'created_at' => date('Y-m-d H:i:s'),
            );
        }
        TrxOrdersDate::simpleQuery()->insert($arr);
        TrxOrdersDate::simpleQuery()->wherein('id',$delete)->delete();
    }
}
