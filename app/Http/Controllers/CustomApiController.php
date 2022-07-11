<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use App\Models\ApiLog;
use App\Models\Banners;
use App\Models\CaloriesIn;
use App\Models\DailyFat;
use App\Models\DashboardCustomers;
use App\Models\Holidays;
use App\Models\LogBackend;
use App\Models\LogNotice;
use App\Models\MasterAlergy;
use App\Models\MasterPackage;
use App\Models\Menus;
use App\Models\MsProduct;
use App\Models\MsQuestion;
use App\Models\Pages;
use App\Models\ProductCategory;
use App\Models\Settings;
use App\Models\TrxOrdersDate;
use App\Models\TrxOrdersPauseDate;
use App\Models\TrxOrders;
use App\Models\TrxOrdersAlergy;
use App\Models\TrxOrdersStatus;
use App\Repositories\CustomersRepository;
use App\Repositories\DashboardCustomersRepository;
use App\Repositories\PackagesRepository;
use App\Repositories\VouchersRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use crocodicstudio\crudbooster\controllers\CBController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Customers;
use App\Models\Vouchers;
use crocodicstudio\crudbooster\helpers\MailHelper;

class CustomApiController extends Controller
{

    public function getCheckVersion(){
        $result['api_status'] = 1;
        $result['api_message'] = 'Success!';

        $android['link'] = Settings::findBySlug('link_android')->getDescription();
        $android['version'] = Settings::findBySlug('version_android')->getDescription();
        $result['android'] = $android;

        $ios['link'] = Settings::findBySlug('link_ios')->getDescription();
        $ios['version'] = Settings::findBySlug('version_ios')->getDescription();
        $result['ios'] = $ios;

        return response()->json($result);
    }

    public function postRegister(){
        $validator = Validator::make(requestAll(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $check = Customers::simpleQuery()->where('email',g('email'))->count();
        if ($check > 0){
            $result['api_status'] = 0;
            $result['api_message'] = 'Email has been registered';

            return response()->json($result);
        }
        $cus = new Customers();
        $cus->setName(g('name'));
        $cus->setHoHp(g('no_wa'));
        $cus->setEmail(g('email'));
        $cus->setTypeCustomer('umum');
        $cus->setPassword(Hash::make(g('password')));
        $cus->save();

        $result['api_status'] = 1;
        $result['api_message'] = 'Success Register';

        return response()->json($result);
    }

    public function postEditProfile(){
        $validator = Validator::make(requestAll(), [
            'name' => 'required',
            'id' => 'required',
            'no_wa' => 'required',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $cus = Customers::findById(g('id'));
        $cus->setName(g('name'));
        $cus->setHoHp(g('no_wa'));
        $cus->setEmail(g('email'));
        $cus->save();

        $result['api_status'] = 1;
        $result['api_message'] = 'Success Update Profile';
        $result['data'] = CustomersRepository::findCustomer(g('id'));

        return response()->json($result);
    }

    public function postBodyMass(){
        $validator = Validator::make(requestAll(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $cus = Customers::findById(g('id'));
        $cus->setGender(g('gender'));
        $cus->setBerat(g('berat'));
        $cus->setTinggi(g('tinggi'));
        $cus->save();

        $result['api_status'] = 1;
        $result['api_message'] = 'Success Update Body Mass Index';
        $result['data'] = CustomersRepository::findCustomer(g('id'));

        return response()->json($result);
    }

    public function postAjukanMahasiswa(){
        $validator = Validator::make(requestAll(), [
            'customer_id' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $cus = Customers::findById(g('customer_id'));
        if(g('photo_krs')){
            $cus->setPhotoKrs(CB()->uploadFile('photo_krs',true));
        }
        if(g('photo_ktm')){
            $cus->setPhotoKtm(CB()->uploadFile('photo_ktm',true));
        }
        $cus->setIsRequest(1);
        $cus->save();
        $customer = CustomersRepository::findCustomer(g('customer_id'));
        $result['api_status'] = 1;
        $result['api_message'] = 'Success melakukan pengajuan,silahkan tunggu untuk konfirmasi';
        $result['data'] = $customer;
        $result['photo_krs'] = $customer->photo_krs;
        $result['photo_ktm'] = $customer->photo_ktm;

        $log = new LogBackend();
        $log->setTrxOrdersId(NULL);
        $log->setCustomersId($cus->getId());
        $log->setContent($cus->getName().' Telah mengajukan akun sebagai mahasiswa');
        $log->save();

        return response()->json($result);

    }

    public function postPhotoProfile(){
        $validator = Validator::make(requestAll(), [
            'customer_id' => 'required',
            'photo' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $cus = Customers::findById(g('customer_id'));
        if(g('photo')){
            $cus->setPhoto(CB()->uploadFile('photo',true));
        }
        $cus->save();
        $customer = CustomersRepository::findCustomer(g('customer_id'));


        $result['api_status'] = 1;
        $result['api_message'] = 'Berhasil mengganti photo profile';
        $result['data'] = $customer;
        $result['photo'] = $customer->photo;
        return response()->json($result);
    }

    public function postUpdatePassword(){
        $validator = Validator::make(requestAll(), [
            'customer_id' => 'required',
            'old_password' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $cus = Customers::findById(g('customer_id'));

        if ($cus){
            if(!Hash::check(g('old_password'),$cus->getPassword())) {
                $result['api_status'] = 0;
                $result['api_message'] = 'Old Password was wrong';

                return response()->json($result);
            }
        }else{
            $result['api_status'] = 0;
            $result['api_message'] = 'Sorry something is wrong';
        }

        $cus->setPassword(Hash::make(g('password')));
        $cus->save();

        $result['api_status'] = 1;
        $result['api_message'] = 'Berhasil mengganti password';
        $result['data'] = CustomersRepository::findCustomer(g('customer_id'));
        return response()->json($result);
    }

    public function postCreateBukuAlamat(){
        $validator = Validator::make(requestAll(), [
            'customer_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $new = new AddressBook();
        $new->setCustomersId(g('customer_id'));
        $new->setName(g('name'));
        $new->setAddress(g('address'));
        $new->setLatitude(g('latitude'));
        $new->setLongitude(g('longitude'));
        $new->setReceiver(g('receiver'));
        $new->setNoPenerima(g('notelp_penerima'));
        $new->setDetailAddress(g('detail_address'));
        $new->setCatatan(g('catatan'));
        $new->save();

        $result['api_status'] = 1;
        $result['api_message'] = 'Success Create Address Book';
        return response()->json($result);
    }

    public function getBanner(){
        $data = Banners::all();
        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $data;

        foreach ($data as $row){
            $row->photo = asset($row->photo);
        }

        return response()->json($result);
    }

    public function postListAddressBook(){
        $validator = Validator::make(requestAll(), [
            'customer_id' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $list = AddressBook::simpleQuery()
            ->where('customers_id',g('customer_id'))
            ->get();

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $list;
        return response()->json($result);
    }

    public function postEditAddressBook(){
        $validator = Validator::make(requestAll(), [
            'customer_id'=>'required',
            'id_address' => 'required',
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $new = AddressBook::findById(g('id_address'));
        $new->setName(g('name'));
        $new->setAddress(g('address'));
        $new->setLatitude(g('latitude'));
        $new->setLongitude(g('longitude'));
        $new->setReceiver(g('receiver'));
        $new->setNoPenerima(g('notelp_penerima'));
        $new->setDetailAddress(g('detail_address'));
        $new->setCatatan(g('catatan'));
        $new->save();

        $list = AddressBook::simpleQuery()
            ->where('customers_id',g('customer_id'))
            ->get();

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $list;
        return response()->json($result);
    }

    public function postDeleteAddressBook(){
        $validator = Validator::make(requestAll(), [
            'customer_id' => 'required',
            'id_address'    => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $find = AddressBook::findById(g('id_address'));
        $find->delete();

        $list = AddressBook::simpleQuery()
            ->where('customers_id',g('customer_id'))
            ->get();

        $result['api_status'] = 1;
        $result['api_message'] = 'success delete address book';
        $result['data'] = $list;
        return response()->json($result);
    }

    public function postMenuHarian(){
        $data = Menus::simpleQuery()
            ->where('menu_date','>=',date('Y-m-d'));
        if (g('date')){
            $data = $data->where('menu_date',g('date'));
        }
        $data = $data->get();
        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $data;

        foreach ($data as $row){
            $row->photo = asset($row->photo);
            $row->alergy = json_decode($row->alergy);
        }

        return response()->json($result);
    }

    public function postlogin(){
        $validator = Validator::make(requestAll(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'regid' => 'required'
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $site = asset('');
        $row = DB::table('customers')
            ->where('email',g('email'))
            ->select('customers.*',
                DB::raw("concat('$site',customers.photo) as photo"))
            ->first();
        if ($row){
            if(!Hash::check(g('password'),$row->password)) {
                $result['api_status'] = 0;
                $result['api_message'] = 'Password is wrong !';
            }else{
                $result['api_status'] = 1;
                $result['api_message'] = 'success';

                $cus = Customers::findById($row->id);
                if (g('os') == 'ios'){
                    $cus->setRegidIos(g('regid'));
                }else{
                    $cus->setRegid(g('regid'));
                }
                $cus->save();
                $row = DB::table('customers')
                    ->where('email',g('email'))
                    ->select('customers.*',
                        DB::raw("concat('$site',customers.photo) as photo"))
                    ->first();
                $result['data'] = $row;
                if (!$row->photo){
                    $row->photo = '';
                }
                if (!$row->tinggi){
                    $row->tinggi = '';
                }
                if (!$row->berat){
                    $row->berat = '';
                }
                if (!$row->tgl_lahir){
                    $row->tgl_lahir = '';
                }
                if (!$row->photo_krs){
                    $row->photo_krs = '';
                }
                if (!$row->photo_ktm){
                    $row->photo_ktm = '';
                }
                if (!$row->status){
                    $row->status = '';
                }
                if (!$row->start_date){
                    $row->start_date = '';
                }
                if (!$row->end_date){
                    $row->end_date = '';
                }
                if (!$row->is_request){
                    $row->is_request = '';
                }
            }
        }else{
            $result['api_status'] = 0;
            $result['api_message'] = 'The email you entered is not registered';
        }
        return response()->json($result);
    }

    public function postPackage(){
        $validator = Validator::make(requestAll(), [
            'type' => 'required',
            'id_customer' => 'required',
//            'periode' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $id_cus = g('id_customer');
        $package = MasterPackage::simpleQuery()
            ->where('type_package',g('type'))
            ->where('periode','!=',1)
            ->get();

        foreach ($package as $row){
            $list = PackagesRepository::findPackage($id_cus,$row->periode,g('type'));

            $row->package = $list;
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $package;
        return response()->json($result);
    }

    public function postPengiriman(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'type' => '',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        if (g('type') == 'ongoing'){
            $site = asset('');
            $list = TrxOrders::simpleQuery()
                ->leftjoin('drivers as d_1','d_1.id','=','trx_orders.drivers_id')
                ->leftjoin('drivers as d_2','d_2.id','=','trx_orders.drivers_id_second')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('customers_id',g('id_customer'))
                ->whereNull('is_paused')
                ->where('must_end','>=',date('Y-m-d'))
                ->where('tgl_mulai','<=',date('Y-m-d'))
                ->where('status_payment','Success Payment')
                ->select('trx_orders.*','d_1.no_wa as no_wa','d_2.no_wa as no_wa_altf','d_1.name as driver_name','d_2.name as driver_name_altf','packages.name as package_name','customers.name as c_name','customers.ho_hp as c_ho_hp')
                ->get();
            $arr = [];

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
            $d = HariApa($date);
            $d = strtolower($d);
            $site = asset('');
            $menus = Menus::simpleQuery()
                ->where('menu_date',date('Y-m-d'))
                ->select('menus.*',DB::raw("concat('$site',menus.photo) as photo"))
                ->get();

            foreach ($menus as $menu){
                $menu->alergy = json_decode($menu->alergy,true);
                if (!empty($menu->alergy)){
                    foreach ($menu->alergy as $r){
                        $check = MasterAlergy::simpleQuery()->where('name',$r['alergy'])->first();
                        if ($check){
                            $arg[] = array(
                                'name'=>$r['alergy'],
                                'photo' => asset($check->photo),
                            );
                        }else{
                            $arg = [];
                        }

                    }
                    $menu->alergy = $arg;
                }
            }

            foreach ($list as $row){
                if (!$row->nama_penerima){
                    $row->nama_penerima = $row->c_name;
                }
                if (!$row->no_penerima){
                    $row->no_penerima = $row->c_ho_hp;
                }
                $off = json_decode($row->day_off);
                $off_d = [];
                $df = '';
                if ($date <= $row->must_end){
                    if ($off){
                        foreach ($off as $y){
                            $off_d[] = $y->day_off;
                            $df .= strtolower($y->day_off).',';
                        }
                    }else{
                        $off_d = [];
                    }
                    if (!in_array($d,$off_d)){
                        $check_status =TrxOrdersStatus::simpleQuery()
                            ->whereDate('date',$date)
                            ->where('trx_orders_id',$row->id)
                            ->orderBy('id','desc')
                            ->first();
                        if ($check_status){
                            if($check_status->status_pengiriman != 'Selesai'){
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

                                $alt = explode(",",$row->day_for_altf);

                                if (in_array($d,$alt)){
                                    $name_address = $row->address_book_id;
                                    $alamat = $row->address_second;
                                    $detail_address = $row->detail_address_second;
                                    $type_alamat = 2;
                                    $driver_now = $row->drivers_id_second;
                                    $driver_name = $row->driver_name_altf;
                                    $driver_no = $row->no_wa;
                                }else{
                                    $name_address = $row->address_name_second;
                                    $alamat = $row->address;
                                    $detail_address = $row->detail_address;
                                    $type_alamat = 1;
                                    $driver_now = $row->drivers_id;
                                    $driver_name = $row->driver_name;
                                    $driver_no = $row->no_wa_altf;
                                }

                                $get_alergy = TrxOrdersAlergy::simpleQuery()
                                    ->where('trx_orders_id',$row->id)
                                    ->join('master_alergy','master_alergy.id','=','trx_orders_alergy.master_alergy_id')
                                    ->select('name',DB::raw("concat('$site',master_alergy.photo) as photo"))
                                    ->get();

                                $arr[] = array(
                                    'id'=>$row->id,
                                    'date'=>$date,
                                    'no_order' => $row->no_order,
                                    'package_name' => $row->package_name,
                                    'periode'=>$row->periode,
                                    'nama_penerima' => $row->nama_penerima,
                                    'no_penerima' => $row->no_penerima,
                                    'nama alamat' => $name_address,
                                    'address' => $alamat,
                                    'detail_address' => $detail_address,
                                    'protein' => $row->protein,
                                    'carbo' => $row->carbo,
                                    'price' => $row->price,
                                    'driver_photo' => 'https://olagi.org/i/2019/08/courier-delivery-bag-caviar-bag-discount-code-uber-eats-delivery-bag-postmates-bag-size-caviar-thermal-bag-coupon-code.jpg',
                                    'driver_name'=>$driver_name,
                                    'no_wa_driver'=>$driver_no,
                                    'day_off' => rtrim($df, ", "),
                                    'day_for' => $row->day_for,
                                    'catatan'=>$row->catatan,
                                    'status_pengiriman' => $status,
                                    'alergy'=>$get_alergy,
                                    'menu' => $menus,
                                    'photo_pengiriman' => $photo_pengiriman,
                                    'nama_penerima_pesanan' => $nama_penerima_pesanan,
                                    'catatan_driver' => $catatan_driver,
                                );
                            }
                        }
                    }
                }
            }
        }else{
            $site = asset('');
            $list = TrxOrdersStatus::simpleQuery()
                ->orderBy('trx_orders_status.date','desc')
                ->join('trx_orders','trx_orders.id','=','trx_orders_status.trx_orders_id')
                ->join('drivers','drivers.id','=','trx_orders.drivers_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
                ->where('trx_orders.customers_id',g('id_customer'))
                ->where('trx_orders_status.date','<',date('Y-m-d'))
                ->select(
                    'trx_orders_status.id',
                    'trx_orders_status.trx_orders_id',
                    'trx_orders_status.date',
                    'trx_orders.periode',
                    'trx_orders.no_order',
                    'trx_orders.nama_penerima',
                    'trx_orders.no_penerima',
                    'trx_orders.address_book_id',
                    'trx_orders.address',
                    'trx_orders.detail_address',
                    'trx_orders.protein',
                    'trx_orders.day_off',
                    'trx_orders.carbo',
                    'trx_orders.price',
                    'trx_orders.day_for',
                    'trx_orders.catatan',
                    'trx_orders.nama_penerima',
                    'trx_orders.nama_penerima',
                    'drivers.name as driver_name',
                    'drivers.no_wa as no_wa_driver',
                    'packages.name as package_name',
                    'customers.name as c_name',
                    'trx_orders_status.status_pengiriman as status_pengiriman',
                    'customers.ho_hp as c_ho_hp',
                    'trx_orders_status.photo_pengiriman as photo_pengiriman',
                    'trx_orders_status.penerima_pengiriman as nama_penerima_pesanan',
                    'trx_orders_status.catatan_pengiriman as catatan_driver'
                )
                ->get();
            if($list){
                foreach ($list as $row){
                    if (!$row->nama_penerima){
                        $row->nama_penerima = $row->c_name;
                    }
                    if (!$row->no_penerima){
                        $row->no_penerima = $row->c_ho_hp;
                    }
                    $row->photo_pengiriman = asset($row->photo_pengiriman);
                    $off = json_decode($row->day_off);
                    $off_d = [];
                    $df = '';
                    if ($off){
                        foreach ($off as $y){
                            $off_d[] = $y->day_off;
                            $df .= strtolower($y->day_off).',';
                        }
                    }else{
                        $off_d = [];
                    }
                    $get_alergy = TrxOrdersAlergy::simpleQuery()
                        ->where('trx_orders_id',$row->trx_orders_id)
                        ->join('master_alergy','master_alergy.id','=','trx_orders_alergy.master_alergy_id')
                        ->select('name',DB::raw("concat('$site',master_alergy.photo) as photo"))
                        ->get();
                    $row->day_off = rtrim($df, ", ");
                    $row->alergy = $get_alergy;
                    $menus = Menus::simpleQuery()
                        ->where('menu_date',$row->date)
                        ->select('menus.*',DB::raw("concat('$site',menus.photo) as photo"))
                        ->get();
                    $row->menu = $menus;
                    foreach ($menus as $menu){
                        $menu->alergy = json_decode($menu->alergy,true);
                        if (!empty($menu->alergy)){
                            foreach ($menu->alergy as $r){
                                $check = MasterAlergy::simpleQuery()->where('name',$r['alergy'])->first();
                                if ($check){
                                    $arg[] = array(
                                        'name'=>$r['alergy'],
                                        'photo' => asset($check->photo),
                                    );
                                }else{
                                    $arg = [];
                                }

                            }
                            $menu->alergy = $arg;
                        }
                    }
                }
            }
            $arr = $list;
        }
        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data']= $arr;

        return response()->json($result);
    }

    public function postDetailPengiriman(){
        $validator = Validator::make(requestAll(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $site = asset('');
        $row = TrxOrdersStatus::simpleQuery()
            ->join('trx_orders','trx_orders.id','=','trx_orders_status.trx_orders_id')
            ->join('drivers','drivers.id','=','trx_orders.drivers_id')
            ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
            ->join('customers','customers.id','=','trx_orders.customers_id')
            ->where('trx_orders.id',g('id'))
            ->orderBy('trx_orders_status.date','desc')
            ->orderBy('trx_orders_status.id','desc')
            ->select(
                'trx_orders_status.id',
                'trx_orders_status.trx_orders_id',
                'trx_orders_status.date',
                'trx_orders.periode',
                'trx_orders.no_order',
                'trx_orders.nama_penerima',
                'trx_orders.no_penerima',
                'trx_orders.address_book_id',
                'trx_orders.address',
                'trx_orders.detail_address',
                'trx_orders.protein',
                'trx_orders.day_off',
                'trx_orders.carbo',
                'trx_orders.price',
                'trx_orders.day_for',
                'trx_orders.catatan',
                'drivers.name as driver_name',
                'drivers.no_wa as no_wa_driver',
                'packages.name as package_name',
                'customers.name as c_name',
                'trx_orders_status.status_pengiriman as status_pengiriman',
                'customers.ho_hp as c_ho_hp',
                'trx_orders_status.photo_pengiriman as photo_pengiriman',
                'trx_orders_status.penerima_pengiriman as nama_penerima_pesanan',
                'trx_orders_status.catatan_pengiriman as catatan_driver'
            )
            ->first();
        if (!$row->nama_penerima){
            $row->nama_penerima = $row->c_name;
        }
        if (!$row->no_penerima){
            $row->no_penerima = $row->c_ho_hp;
        }
        if ($row->photo_pengiriman){
            $row->photo_pengiriman = asset($row->photo_pengiriman);
        }else{
            $row->photo_pengiriman = '';
            $row->nama_penerima_pesanan = "";
            $row->catatan_driver = "";
        }
        $off = json_decode($row->day_off);
        $off_d = [];
        $df = '';
        if ($off){
            foreach ($off as $y){
                $off_d[] = $y->day_off;
                $df .= strtolower($y->day_off).',';
            }
        }else{
            $off_d = [];
        }
        $get_alergy = TrxOrdersAlergy::simpleQuery()
            ->where('trx_orders_id',$row->trx_orders_id)
            ->join('master_alergy','master_alergy.id','=','trx_orders_alergy.master_alergy_id')
            ->select('name',DB::raw("concat('$site',master_alergy.photo) as photo"))
            ->get();
        $row->day_off = rtrim($df, ", ");
        $row->alergy = $get_alergy;
        $menus = Menus::simpleQuery()
            ->where('menu_date',$row->date)
            ->select('menus.*',DB::raw("concat('$site',menus.photo) as photo"))
            ->get();
        $row->menu = $menus;
        foreach ($menus as $menu){
            $menu->alergy = json_decode($menu->alergy,true);
            if (!empty($menu->alergy)){
                foreach ($menu->alergy as $r){
                    $check = MasterAlergy::simpleQuery()->where('name',$r['alergy'])->first();
                    $arg[] = array(
                        'name'=>$r['alergy'],
                        'photo' => asset($check->photo),
                    );
                }
                $menu->alergy = $arg;
            }
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data']= $row;

        return response()->json($result);
    }

    public function getSetPauseAll(){
        $id = [5,11,51,127,203,134,266,296,342,396];
        $list = TrxOrders::simpleQuery()
            ->where('must_end','>',date('Y-m-d'))
            ->whereNotIn('trx_orders.id',$id)
            ->where('trx_orders.status_payment','Success Payment')
            ->get();

        foreach ($list as $row){
            $check = DB::table('trx_orders_status')
                ->groupBy('date')
                ->where('trx_orders_id',$row->id)
                ->count();
            if ($check < $row->periode){
                $update['is_paused'] = NULL;
                DB::table('trx_orders')->where('id',$row->id)->update($update);
            }
//
        }

        dd($list);
    }
//    new
    public function postOrder(){
        $validator = Validator::make(requestAll(), [
            'date_start' => 'required|date_format:Y-m-d',
            'id_customer' => 'required',
            'periode' => 'required',
            'package_id' => 'required',
            'price' => 'required',
            'nama_alamat' => 'required',
            'alamat' => 'required',
            'detail_address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'day_for' => '',
            'nama_alamat_second' => '',
            'alamat_second' => '',
            'detail_address_second' => '',
            'latitude_second' => '',
            'longitude_second' => '',
            'day_for_altf' => '',
            'date_order' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        $date_order = explode(",",g('date_order'));
        $ttl_array = count($date_order);

        if ($ttl_array < g('periode')){
            $sisa = g('periode') - count($date_order);
            $result['api_status'] = 0;
            $result['api_message'] = 'Mohon tentukan tanggal order anda sisa ('.$sisa.')';
            return response()->json($result);
            exit();
        }elseif ($ttl_array > g('periode')){
            $sisa = count($date_order) - g('periode');
            $result['api_status'] = 0;
            $result['api_message'] = 'Mohon tentukan tanggal order anda kelebihan ('.$sisa.')';
            return response()->json($result);
            exit();
        }

        $order = new TrxOrders();
        $order->setCreatedAt(date('Y-m-d H:i:s'));
        $order->setTglMulai(g('date_start'));
        $order->setNoOrder('EZFIT'.time());
        $order->setCustomersId(g('id_customer'));
        $order->setTypeApps('News');
        $order->setPeriode(g('periode'));
        $order->setPackagesId(g('package_id'));
        if (g('vouchers_code') != NULL) {
            VouchersRepository::subQuota(g('vouchers_code'));
            $order->setVouchersCode(g('vouchers_code'));
        }
        $order->setPrice(g('price'));
        $order->setAddressBookId(g('nama_alamat'));
        $order->setAddress(g('alamat'));
        $order->setDetailAddress(g('detail_address'));
        $order->setLatitude(g('latitude'));
        $order->setLongitude(g('longitude'));
        $order->setPaymentMethod('direct transfer');
        $order->setStatusPayment('Waiting Payment');
        $order->setCatatan(g('catatan'));
        $order->setDayFor(g('day_for'));
        $order->setMustEnd(end($date_order));

        if (g('nama_alamat_second')){
            $order->setAddressNameSecond(g('nama_alamat_second'));
            $order->setAddressSecond(g('alamat_second'));
            $order->setDetailAddressSecond(g('detail_address_second'));
            $order->setLatitudeSecond(g('latitude_second'));
            $order->setLongitudeSecond(g('longitude_second'));
            $order->setDayForAltf(g('day_for_second'));
            $order->setCatatanAltf(g('catatan_second'));
        }
        $voucher = VouchersRepository::getDiscountByCode(g('vouchers_code'),g('price'));
        if ($voucher) {
            $order->setPrice(g('price'));
            $order->setTotal(g('price') - $voucher);
        }else{
            $order->setPrice(g('price'));
            $order->setTotal(g('price'));
        }
        $order->save();
        $jsonnya = str_replace("&amp",'"',g('alergen'));
        $jsonnya = str_replace(";#34;","",$jsonnya);
        $alrg = json_decode($jsonnya,true);
        if(!empty($alrg)){
            foreach ($alrg as $row){
                $arr_er[] = array(
                    'trx_orders_id' => $order->getId(),
                    'master_alergy_id' => $row['alergy_id']
                );
            }

            DB::table('trx_orders_alergy')->insert($arr_er);
        }
        if ($order){
            $arr_save = [];
            foreach ($date_order as $d_order){
                $arr_save[] = array(
                    'trx_orders_id' =>  $order->getId(),
                    'date' =>  $d_order,
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }

            DB::table('trx_orders_date')->insert($arr_save);

            $last = DB::table('trx_orders_date')->where('trx_orders_id', $order->getId())->orderBy('date','desc')->first();

            $updates = TrxOrders::findById($order->getId());
            $updates->setMustEnd($last->date);
            $updates->save();

            $log = new LogBackend();
            $log->setTrxOrdersId($order->getId());
            $log->setCustomersId($order->getCustomersId()->getId());
            $log->setContent($order->getCustomersId()->getName().' Telah melakukan pembelian');
            $log->save();
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        return response()->json($result);

    }

    public function postCreateOrder(){
        $validator = Validator::make(requestAll(), [
            'date_start' => 'required',
            'id_customer' => 'required',
            'periode' => 'required',
            'package_id' => 'required',
            'price' => 'required',
            'nama_alamat' => 'required',
            'alamat' => 'required',
            'detail_address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'day_for' => '',
            'nama_alamat_second' => '',
            'alamat_second' => '',
            'detail_address_second' => '',
            'latitude_second' => '',
            'longitude_second' => '',
            'day_for_altf' => '',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        // tgl 24 desember - tgl 5 januari
        if (g('date_start') >= '2019-12-23' && g('date_start') <= '2020-01-05'){
            $result['api_status'] = 0;
            $result['api_message'] = 'Mohon maaf EZ FIT sedang libur (24-12-19 s/d 05-01-20)';
            return response()->json($result);
            exit();
        }

        $arr = [];
        $end = getRealEnd(g('date_start'),g('periode'),json_encode($arr));
        $order = new TrxOrders();
        $order->setCreatedAt(date('Y-m-d H:i:s'));
        $order->setTglMulai(g('date_start'));
        $order->setTypeApps('Old');
        $order->setNoOrder('EZFIT'.time());
        $order->setCustomersId(g('id_customer'));
        $order->setPeriode(g('periode'));
        $order->setPackagesId(g('package_id'));
        if (g('vouchers_code') != NULL) {
            VouchersRepository::subQuota(g('vouchers_code'));
            $order->setVouchersCode(g('vouchers_code'));
        }
        $order->setPrice(g('price'));
        $order->setAddressBookId(g('nama_alamat'));
        $order->setAddress(g('alamat'));
        $order->setDetailAddress(g('detail_address'));
        $order->setLatitude(g('latitude'));
        $order->setLongitude(g('longitude'));
        $order->setPaymentMethod('direct transfer');
        $order->setStatusPayment('Waiting Payment');
        $order->setCatatan(g('catatan'));
        $order->setDayFor(g('day_for'));
        $order->setMustEnd($end);
        if (g('nama_alamat_second')){
            $order->setAddressNameSecond(g('nama_alamat_second'));
            $order->setAddressSecond(g('alamat_second'));
            $order->setDetailAddressSecond(g('detail_address_second'));
            $order->setLatitudeSecond(g('latitude_second'));
            $order->setLongitudeSecond(g('longitude_second'));
            $order->setDayForAltf(g('day_for_second'));
            $order->setCatatanAltf(g('catatan_second'));
        }
        $voucher = VouchersRepository::getDiscountByCode(g('vouchers_code'),g('price'));
        if ($voucher) {
            $order->setPrice(g('price'));
            $order->setTotal(g('price') - $voucher);
        }else{
            $order->setPrice(g('price'));
            $order->setTotal(g('price'));
        }
        $order->save();
        $jsonnya = str_replace("&amp",'"',g('alergen'));
        $jsonnya = str_replace(";#34;","",$jsonnya);
        $alrg = json_decode($jsonnya,true);
        if(!empty($alrg)){
            foreach ($alrg as $row){
                $arr_er[] = array(
                    'trx_orders_id' => $order->getId(),
                    'master_alergy_id' => $row['alergy_id']
                );
            }

            DB::table('trx_orders_alergy')->insert($arr_er);
        }
        if ($order){
            $log = new LogBackend();
            $log->setTrxOrdersId($order->getId());
            $log->setCustomersId($order->getCustomersId()->getId());
            $log->setContent($order->getCustomersId()->getName().' Telah melakukan pembelian');
            $log->save();
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        return response()->json($result);

    }

    public function getCheckPer(){
        $result = VouchersRepository::getDiscountByCode(g('code'),20000);
        return $result;
    }

    public function getTest(){
        $find = Customers::findById(g('id'));
        $regID = array($find->getRegid());
        $data['title'] = 'test';
        $data['content'] = 'test';
        $apikey = 'AAAAMcr5z0I:APA91bECNwRDn63pxC_tMYGA2hYCC5xh_E2q7bp-DsCoVDlZUpznyV7otLQnOxiAR9fI4S_4NKL6QLDbnIPzUpnkicDh2Ax81rwTsp4ys_Hr0QqwZGvrg4DfawECoJN8jrZT5EYRTV4z';
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = [
            'registration_ids' => $regID,
            'data' => $data,
            'content_available' => true,
            'notification' => [
                'sound' => 'default',
                'badge' => 0,
                'title' => trim(strip_tags($data['title'])),
                'body' => trim(strip_tags($data['content'])),
            ],
            'priority' => 'high',
        ];
        $headers = [
            'Authorization:key='.$apikey,
            'Content-Type:application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $chresult = curl_exec($ch);
        curl_close($ch);

        dd($chresult);
    }

    public function postAlergen(){
        $alergen = MasterAlergy::all();
        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $alergen;
        foreach ($alergen as $row){
            $row->photo = asset($row->photo);
        }
        return response()->json($result);
    }

    public function postListOrder(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        if (date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')){
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
        $list = TrxOrders::simpleQuery()
            ->leftJoin('drivers','drivers.id','=','trx_orders.drivers_id')
            ->leftJoin('packages','packages.id','=','trx_orders.packages_id')
            ->select('trx_orders.*',
                'drivers.name as driver_name',
                'packages.name as package_name',
                DB::raw("concat('$site',packages.photo) as photo")
            );

        if (g('status_order') == 'history'){
            $list = $list
                ->where('customers_id',g('id_customer'))
                ->where(function ($query) {
                    $query
                        ->where('must_end','<',date('Y-m-d'))
                        ->whereNull('is_paused')
                        ->orWhere('trx_orders.status_payment','Failed');
                });
        }else{
            $list = $list
                ->where('customers_id',g('id_customer'))
                ->where('must_end','>',date('Y-m-d'))
                ->where('trx_orders.status_payment','!=','Failed');
        }

        $list = $list->get();

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $list;
        $site = asset('');
        $now = date('Y-m-d');
        foreach ($list as $row){
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
            $day_off = json_decode($row->day_off,true);
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

            $row->price = $row->total;
            $libur = DB::table('trx_orders_pause_date')
                ->where('trx_orders_id',$row->id)
                ->select('date')
                ->groupBy('date')
                ->pluck('date')
                ->toArray();

            if(!empty($date) && $row->status_payment != 'Failed'){
                $inow = 1;
                foreach ($date as $y){
                    $d = HariApa($y);
                    $d = strtolower($d);
                    if($d != 'minggu'){
                        if (!in_array($d,$off_d)){
                            if (!in_array($y,$libur)){
                                if ($dates <= $y){
                                    $date_next[] = array(
                                        'date' => $y,
                                    );
                                }
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

        }
        return response()->json($result);

    }

    public function postDetailOrder(){
        $validator = Validator::make(requestAll(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        if (date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')){
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
            ->leftJoin('drivers','drivers.id','=','trx_orders.drivers_id')
            ->leftJoin('packages','packages.id','=','trx_orders.packages_id')
            ->where('trx_orders.id',g('id'))
            ->select('trx_orders.*',
                'drivers.name as driver_name',
                'packages.name as package_name',
                DB::raw("concat('$site',packages.photo) as photo")
            );

        if (g('status_order') == 'history'){
            $row = $row
                ->where(function ($query) {
                    $query
                        ->where('must_end','<',date('Y-m-d'))
                        ->whereNull('is_paused')
                        ->orWhere('trx_orders.status_payment','Failed');
                });
        }else{
            $row = $row
                ->where('must_end','>',date('Y-m-d'))
                ->where('trx_orders.status_payment','!=','Failed');
        }

        $row = $row->first();
        $now = date('Y-m-d');

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
        $day_off = json_decode($row->day_off,true);
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
        $row->price = $row->total;

        $result['api_status'] = 1;
        $result['api_message'] = 'data';
        $result['data'] = $row;

        return response()->json($result);

    }

    public function getExcel(){
        $data['list'] = DB::table('trx_orders')
            ->where('status_payment','Success Payment')
            ->get();

        return view('excel',$data);
    }

    public function postPauseOrder(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'order_id' => 'required',
            'day_off'=>'',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        if (date('Y-m-d') >= '2019-12-23' && date('Y-m-d') <= '2020-01-05'){
            $result['api_status'] = 0;
            $result['api_message'] = 'Mohon maaf EZ FIT sedang libur (24-12-19 s/d 05-01-20)';
            return response()->json($result);
            exit();
        }
        if(date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')){
            $result['api_status'] = 0;
            $result['api_message'] = 'Gagal melakukan perubahan hari pengiriman, perubahan maximal jam 8 PM';

            return response()->json($result);
            exit();
        }
        $logs = new ApiLog();
        if (!empty(g('day_off'))){
            $d = explode(',',g('day_off'));
            foreach ($d as $y){
                $arr[] = array(
                    'day_off'=>$y,
                );
                $arr_check[] = strtolower($y);
            }
            $arr = json_encode($arr);
        }else{
            $arr = NULL;
            $arr_check = NULL;
        }
        $logs->setUserId(g('id_customer'));

        $off = TrxOrders::findById(g('order_id'));

        $day_for = array('senin','selasa','rabu','kamis','jumat','sabtu');
        $day_for_result = [];
        if ($arr_check == NULL) {
            $day_for_result = $day_for;
        }else{
            foreach ($day_for as $key => $df) {
                if (!in_array($df, $arr_check)) {
                    $day_for_result[] = $df;
                }
            }
        }

        if (count($day_for_result) <= 1) {
            $dfr = '';
            foreach ($day_for_result as $y) {
                $dfr .= $y;
            }
            // $dfr = $day_for_result;
        }else{
            $dfr = implode(',', $day_for_result);
        }
        $logs->setSegment(g('order_id').' Merubah Hari menjadi: '.$arr);
        $off->setDayFor($dfr);
        $off->setDayOff($arr);
        $end = getRealEnd($off->getTglMulai(),$off->getPeriode(),$arr);
        $off->setMustEnd($end);
        $off->save();

        if ($off){
            $result['api_status'] = 1;
            $result['api_message'] = 'success';
        }else{
            $result['api_status'] = 0;
            $result['api_message'] = 'Failed';
        }
        $logs->setCreatedAt(date('Y-m-d H:i:s'));
        $logs->save();

        return response()->json($result);
    }

    public function postPauseCustomer(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'order_id' => 'required',
            'pause'=>'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        if (date('Y-m-d') >= '2019-12-23' && date('Y-m-d') <= '2020-01-05'){
            $result['api_status'] = 0;
            $result['api_message'] = 'Mohon maaf EZ FIT sedang libur (24-12-19 s/d 05-01-20)';
            return response()->json($result);
            exit();
        }
        $logs = new ApiLog();
        $logs->setUserId(g('id_customer'));
        $check = TrxOrders::findById(g('order_id'));
        if ($check){
            if (g('pause') == 'pause'){
                if (date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')){
                    $result['api_status'] = 2;
                    $result['api_message'] = array(
                        'title'=>'Penundaan Langganan Ditolak',
                        'message'=> [
                            array(
                                'type' => 'normal',
                                'text' => 'Penundaan langganan catering hanya bisa dilakukan diantara',
                            ),array(
                                'type'=> 'bold',
                                'text' => 'pukul 00.01 sampai 19:59',
                            )]
                    );
                }else{
                    $check->setIsPaused(1);
                    $result['api_status'] = 1;
                    $result['api_message'] = array(
                        'title'=>'Langganan Ditunda',
                        'message'=> [
                            array(
                                'type' => 'normal',
                                'text' => 'Langganan kamu selanjutnya akan ditunda dan tidak akan dikirim',
                            )]
                    );
                    $logs->setSegment('Langganan Ditunda'.g('order_id'));
                }
            }else{
                $check->setIsPaused(NULL);
                $result['api_status'] = 1;
                $result['api_message'] = array(
                    'title'=>'Langganan Dimulai',
                    'message'=> [
                        array(
                            'type' => 'normal',
                            'text' => 'Kamu telah mulai berlangganan kembali dan katering akan dikirim besok',
                        ),array(
                            'type'=> 'bold',
                            'text' => "Masih Dummy",
//                            HariApa(date('d')).', '.date('d M Y')
                        )]
                );
                $logs->setSegment('Langganan Dimulai '.g('order_id'));
            }
            $logs->setCreatedAt(date('Y-m-d H:i:s'));
            $logs->save();
            $check->save();
        }else{
            $result['api_status'] = 0;
            $result['api_message'] = 'Failed';
        }
        return response()->json($result);
    }

    public function getSettingBank(){
        $photo = DB::table('settings')->where('slug','photo')->first();
        $bank_area = DB::table('settings')->where('slug','bank_area')->first();
        $nomor_rekening = DB::table('settings')->where('slug','nomor_rekening')->first();
        $atas_nama = DB::table('settings')->where('slug','atas_nama')->first();
        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['photo'] = asset($photo->description);
        $result['bank_area'] = $bank_area->description;
        $result['nomor_rekening'] = $nomor_rekening->description;
        $result['atas_nama'] = $atas_nama->description;

        return response()->json($result);

    }

    public function postUpdatePayment(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'order_id' => 'required',
            'no_pemilik_rek'=>'required',
            'nama_pemilik_rek'=>'required',
            'photo_payment'=>'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $order = TrxOrders::findById(g('order_id'));
        $order->setNoRek(g('no_pemilik_rek'));
        $order->setNamaRek(g('nama_pemilik_rek'));
        $order->setDatePayment(date('Y-m-d H:i:s'));
        $order->setPhotoPayment(CB()->uploadFile('photo_payment',true));
        $order->setStatusPayment('Confirmation');
        $up = $order->save();

        if ($up){
            $log = new LogBackend();
            $log->setTrxOrdersId($order->getId());
            $log->setCustomersId($order->getCustomersId()->getId());
            $log->setContent($order->getCustomersId()->getName().' Telah melakukan pembayaran ');
            $log->save();

            $result['api_status'] = 1;
            $result['api_message'] = 'success';
        }else{
            $result['api_status'] = 0;
            $result['api_message'] = 'Failed';
        }

        return response()->json($result);
    }

    public function postPages(){
        $validator = Validator::make(requestAll(), [
            'page' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $data = Pages::simpleQuery()
            ->where('slug',g('page'))
            ->first();

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $data;

        return response()->json($result);
    }

    public function postCheckTime(){
        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['time'] = date('Y-m-d H:i:s');
        $result['max_time_order'] = date('19:55:00');

        return response()->json($result);
    }

    public function getCheckNotice(){
        $check = LogBackend::simpleQuery()
            ->whereNull('is_read')
            ->get();

        $data['total'] = count($check);
        $data['items'] = $check;

        return response()->json($data);
    }

    public function postCheckVoucher(){
        $code = g('voucher_code');
        $customer = g('customer_id');
        $nominal = g('nominal');
        $date = date('Y-m-d');
        $check_user = Customers::findById($customer);

        $check_voucher = Vouchers::simpleQuery()
            ->where('code',$code)
            ->first();

        if ($check_voucher) {
            if ($check_voucher->type_voucher != 'semua'){
                if ($check_voucher->type_voucher != $check_user->getTypeCustomer()){
                    $result['api_status'] = 0;
                    $result['api_message'] = 'Vouchers Cannot Be Used!';
                    return response()->json($result);
                    exit();
                }
            }

            $check = Vouchers::simpleQuery()
                ->where('code',$code)
                ->whereDate('date_start', '<=', date("Y-m-d"))
                ->whereDate('date_end', '>=', date("Y-m-d"))
                ->first();

            if ($check) {
                if ($check->quota == 0) {
                    $result['api_status'] = 0;
                    $result['api_message'] = 'Use of Vouchers Has Exceeded Limits!';
                }else{
                    $not_available = TrxOrders::simpleQuery()
                        ->where('customers_id',$customer)
                        ->where('vouchers_code',$code)
                        ->first();

                    if ($not_available) {
                        $result['api_status'] = 0;
                        $result['api_message'] = 'Vouchers Cannot Be Used!';
                    }else{
                        $result['api_status'] = 1;
                        $result['api_message'] = 'Success';
                        $result['discount'] = VouchersRepository::getDiscountByCode($code,$nominal);
                        $result['total'] = $nominal - $result['discount'];
                    }
                }
            }else{
                $result['api_status'] = 0;
                $result['api_message'] = 'Vouchers Expired!';
            }
        }else{
            $result['api_status'] = 0;
            $result['api_message'] = 'Voucher Not Found!';
        }
        return response()->json($result);
    }

    public function postForgotPassword(){
        $validator = Validator::make(requestAll(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        $check = Customers::simpleQuery()
            ->where('email',g('email'))
            ->first();

        if($check){
            $pass = strRandom(6);
            $update = Customers::findById($check->id);
            $update->setPassword(Hash::make($pass));
            $update->save();

            $subject = 'Reset Password';
            $content = 'Reset Password';

            $mail = new MailHelper();
            $mail->sender('noreply@ezfit.net','Ezfit');
            $mail->to(g('email'));
            $mail->setName($check->name);
            $mail->setPassword($pass);
            $mail->content($subject, $content);
            $mail->sendPassword();

            $result['api_status'] = 1;
            $result['api_message'] = 'success send email';
        }else{
            $result['api_status'] = 0;
            $result['api_message'] = 'Email tidak ditemukan';
        }

        return response()->json($result);
    }

    public function postListNotice(){
        $validator = Validator::make(requestAll(), [
            'customer_id' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        $list = LogNotice::simpleQuery()
            ->where('customers_id',g('customer_id'))
            ->orderBy('id','desc')
            ->get();

        $result['api_status'] = 1;
        $result['api_message'] = 'data';
        $result['data'] = $list;

        return response()->json($result);
    }

    public function postDeleteNotice(){
        $validator = Validator::make(requestAll(), [
            'id' => 'required',
            'customer_id' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $check = LogNotice::findById(g('id'));
        if ($check->getCustomersId()->getId() == g('customer_id')){
            $result['api_status'] = 1;
            $result['api_message'] = 'data';
            LogNotice::simpleQuery()->where('id',g('id'))->delete();
        }else{
            $result['api_status'] = 0;
            $result['api_message'] = 'You dont have access to delete it';
        }
        $list = LogNotice::simpleQuery()
            ->where('customers_id',g('customer_id'))
            ->orderBy('id','desc')
            ->get();


        $result['data'] = $list;
        return response()->json($result);
    }

    public function getNotice($id){
        $type = g('type');
        $user = Customers::findById(10);
        $order = DB::table('trx_orders')->where('customers_id',10)->first();
        $regids[] = $user->getRegid();
        $regid_ioss[] = $user->getRegidIos();
        if ($type == 'order_expired'){
            $content = 'Status order anda akan segera selesai';
            $data['title'] = 'Pemberitahuan Order';
            $mess['type_notice'] = 'ongoing';
            $mess['content'] = $content;
            $mess['type']   = 'order_expired';
            $data['content'] = $content;
            $data['data'] = $mess;
            if ($regids){
                $notice[] = SendFcm($regids,$data,'IOS');
            }
            if ($regid_ioss){
                $notice[] = SendFcm($regid_ioss,$data,'IOS');
            }
        }elseif ($type == 'account'){
            $data['title'] = 'Account Mahasiswa Expired';
            $content = 'Status mahasiswa pada akun anda sudah kadaluarsa silahkan perbarui';
            $mess['content'] = $content;
            $mess['type']   = 'account';
            $data['content'] = $content;
            $data['data'] = $mess;
            if ($regids){
                $notice[] = SendFcm($regids,$data,'IOS');
            }
            if($regid_ioss){
                $notice[] = SendFcm($regid_ioss,$data,'IOS');
            }
        }elseif($type == 'order_pengiriman'){
            $data['title'] = 'Update Status Pengiriman';
            $mess['type'] = 'order_pengiriman';
            $mess['type_notice'] = 'ongoing';
            $content = 'Status pengiriman anda telah berubah menjadi '.g('status_pengiriman');
            $mess['content'] = $content;
            $mess['id_order'] = $id;
            $data['content'] = $content;
            $data['data'] = $mess;

            $regid_a[] = $user->getRegid();
            $regid_i[] = $user->getRegidIos();
            $notice[] = SendFcm($regid_a,$data,'IOS');
            $notice[] = SendFcm($regid_i,$data,'IOS');
        }else{
            $mess['type'] = 'order';
            $mess['id_order'] = $id;
            $content = 'Status pembayaran pembelian anda dengan code TEST telah diperbarui menjadi status';
            $mess['content'] = $content;
            $mess['type_notice'] = 'ongoing';
            $data['title'] = 'Status Pembayaran';
            $data['content'] = $content;
            $data['data'] = $mess;

            $regid_a[] = $user->getRegid();
            $regid_i[] = $user->getRegidIos();
            $notice[] = SendFcm($regid_a,$data,'IOS');
            $notice[] = SendFcm($regid_i,$data,'IOS');
        }
        dd($notice);
    }

    public function postLogout(){
        $validator = Validator::make(requestAll(), [
            'id' => 'required',
            'os' => 'required'
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        $check = Customers::findById(g('id'));
        if(g('os') == 'IOS'){
            $check->setRegidIos(NULL);
        }else{
            $check->setRegid(NULL);
        }
        $check->save();

        $result['api_status'] = 1;
        $result['api_message'] = 'success logout';

        return response()->json($result);
    }

    public function getExpiredMhs(){
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
            $update_status['photo_krs'] = NULL;
            $update_status['photo_ktm'] = NULL;
            $update_status['type_customer'] = 'umum';

            DB::table('customers')->wherein('id',$id_reg)->update($update_status);
        }

        $data_account['title'] = 'Account Mahasiswa Expired';
        $conten_e = 'Status mahasiswa pada akun anda sudah kadaluarsa silahkan perbarui';
        $messme['content'] = $conten_e;
        $messme['type']   = 'account';
        $data_account['content'] = $conten_e;
        $data_account['content'] = $messme;
        $send = [];
        if ($regid){
            $send[] = SendFcm($regid,$data_account,'IOS');
        }
        if($regidIos){
            $send[] = SendFcm($regidIos,$data_account,'IOS');
        }
        dd($send);

    }

    public function getCheckOrder(){
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
            $android = SendFcm($regids,$data_expired,'IOS');
        }
        if ($regid_ioss){
            $ios = SendFcm($regid_ioss,$data_expired,'IOS');
        }
        dd($order,$regids,$regid_ioss,$android,$ios);
    }

    public function getCheck(){
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
    }

    public function getCron(){
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
        $check = DB::table('this_day_log')->where('date',$date)->count();
        if($check == 0 ){
            $order = TrxOrders::simpleQuery()
                ->leftjoin(
                    DB::raw("(SELECT trx_orders_status.trx_orders_id,count(trx_orders_status.id) as total_pengiriman 
                        FROM trx_orders_status GROUP BY trx_orders_status.trx_orders_id) as total_pengiriman"),
                    "total_pengiriman.trx_orders_id","=","trx_orders.id")
                ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->join('customers','customers.id','=','trx_orders.customers_id')
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
            $inse['date'] = $date;
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

        $list = TrxOrdersDate::simpleQuery()
            ->join('trx_orders','trx_orders.id','trx_orders_date.trx_orders_id')
            ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
            ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
            ->leftjoin('customers','customers.id','=','trx_orders.customers_id')
            ->where('trx_orders.status_payment','!=','Failed')
            ->where('date',$date)
            ->whereNull('trx_orders.is_paused')
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

        $regid_a = [];
        $regid_i = [];
        $logs = [];
        foreach ($list as $row){
            if ($row->status_payment == 'Success Payment'){
                if (!$row->nama_penerima){
                    $row->nama_penerima = $row->c_name;
                }
                if (!$row->no_penerima){
                    $row->no_penerima = $row->c_ho_hp;
                }
                $check = DB::table('trx_orders_status')
                    ->where('trx_orders_id',$row->id)
                    ->where('status_pengiriman','Proses')
                    ->whereDate('date', $date)
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

                    if ($regid_a){
                        $logs[] = SendFcm($regid_a,$data,'IOS');
                    }
                    if($regid_i){
                        $logs[] = SendFcm($regid_i,$data,'IOS');
                    }

                    $log = new LogNotice();
                    $log->setCustomersId($row->customers_id);
                    $log->setTrxOrdersId($row->id);
                    $log->setTitle('Status Pengiriman');
                    $log->setTypeNotice('ongoing');
                    $log->setContent($content);
                    $log->setCreatedAt(date('Y-m-d H:i:s'));
                    $log->save();
                }
            }elseif($row->status_payment == 'Waiting Payment'){
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

                    if ($regid_a){
                        $logs[] = SendFcm($regid_a,$data,'IOS');
                    }
                    if($regid_i){
                        $logs[] = SendFcm($regid_i,$data,'IOS');
                    }

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

        dd($regid_i,$regid_a,$logs);
    }
    public function postListOrderV2(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        if (date('Y-m-d H:i:s') >= date('Y-m-d 19:55:00')){
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
        $list = TrxOrders::simpleQuery()
            ->leftJoin('drivers','drivers.id','=','trx_orders.drivers_id')
            ->leftJoin('packages','packages.id','=','trx_orders.packages_id')
            ->select('trx_orders.*',
                'drivers.name as driver_name',
                'packages.name as package_name',
                DB::raw("concat('$site',packages.photo) as photo")
            );
        if (g('status_order') == 'history'){
            $list = $list
                ->where('customers_id',g('id_customer'))
                ->where(function ($query) {
                    $query
                        ->whereNull('is_paused')
                        ->orWhere('trx_orders.status_payment','Failed');
                });
        }else{
            $list = $list
                ->where('customers_id',g('id_customer'))
                ->where('trx_orders.status_payment','!=','Failed');
        }

        $list = $list->get();
        $site = asset('');
        $now = date('Y-m-d');
        $arr = [];
        foreach ($list as $row){
            $alergy = TrxOrdersAlergy::simpleQuery()
                ->join('master_alergy','master_alergy.id','=','trx_orders_alergy.master_alergy_id')
                ->select('name',DB::raw("concat('$site',master_alergy.photo) as photo"))
                ->where('trx_orders_id',$row->id)
                ->get();
            $day_off = json_decode($row->day_off,true);
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
            $date_before = NULL;
            $date_next = NULL;
            $libur = DB::table('trx_orders_pause_date')
                ->where('trx_orders_id',$row->id)
                ->select('date')
                ->groupBy('date')
                ->pluck('date')
                ->toArray();

            if(!empty($date) && $row->status_payment != 'Failed'){
                $date_next = TrxOrdersDate::simpleQuery()
                    ->where('trx_orders_id',$row->id)
                    ->where('date','>',$dates)
                    ->select('date')
                    ->get();
            }
            $find_riwayat = TrxOrdersDate::simpleQuery()
                ->where('trx_orders_id',$row->id)
                ->select('date')
                ->get();
            $ttl = 0;
            $riwayat = null;
            $next = null;
            foreach ($find_riwayat as $y){
                $cheking = TrxOrdersStatus::simpleQuery()
                    ->where('trx_orders_id',$row->id)
                    ->where('date',$y->date)
                    ->count();
                if($cheking > 0){
                    $ttl += 1;
                    $riwayat[] = array(
                        'date' => $y->date,
                    );
                }else{
                    $next[] = array(
                        'date' => $y->date,
                    );
                }
            }
            if ($row->is_paused == 1){
                $next = [];
            }
            $d = $row->periode - $ttl;
            if (g('status_order') == 'history'){
                if ($d == 0){
                    $arr[] = array(
                        'id' => $row->id,
                        'customers_id' => $row->customers_id,
                        'periode' => $row->periode,
                        'packages_id' => $row->packages_id,
                        'vouchers_code' => $row->vouchers_code,
                        'payment_method' => $row->payment_method,
                        'tgl_mulai' => $row->tgl_mulai,
                        'protein' => $row->protein,
                        'protein_alternative' => $row->protein_alternative,
                        'carbo' => json_decode($row->carbo,true),
                        'carbo_alternative' => $row->carbo_alternative,
                        'day_off' => "",
                        'address_book_id' => $row->address_book_id,
                        'drivers_id' => $row->drivers_id,
                        'status_berlangganan' => $row->status_berlangganan,
                        'status_payment' => $row->status_payment,
                        'payment_date' => $row->payment_date,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                        'address' => $row->address,
                        'detail_address' => $row->detail_address,
                        'latitude' => $row->latitude,
                        'longitude' => $row->longitude,
                        'nama_penerima' => $row->nama_penerima,
                        'no_penerima' => $row->no_penerima,
                        'no_order' => $row->no_order,
                        'price' => $row->price,
                        'total' => $row->total,
                        'address_name_second' => $row->address_name_second,
                        'address_second' => $row->address_second,
                        'detail_address_second' => $row->detail_address_second,
                        'latitude_second' => $row->latitude_second,
                        'longitude_second' => $row->longitude_second,
                        'nama_penerima_second' => $row->nama_penerima_second,
                        'no_penerima_second' => $row->no_penerima_second,
                        'drivers_id_second' => $row->drivers_id_second,
                        'must_end' => $row->must_end,
                        'day_for' => $row->day_for,
                        'day_for_altf' => $row->day_for_altf,
                        'catatan' => $row->catatan,
                        'catatan_altf' => $row->catatan_altf,
                        'photo_pengiriman' => $row->photo_pengiriman,
                        'nama_penerima_pesanan' => $row->nama_penerima_pesanan,
                        'catatan_driver' => $row->catatan_driver,
                        'is_paused' => $row->is_paused,
                        'date_payment' => $row->date_payment,
                        'no_rek' => $row->no_rek,
                        'nama_rek' => $row->nama_rek,
                        'photo_payment' => $row->photo_payment,
                        'type_apps' => $row->type_apps,
                        'driver_name' => $row->driver_name,
                        'package_name' => $row->package_name,
                        'photo' => $row->photo,
                        'total_send' => $ttl,
                        'day_for_second' => $row->day_for_altf,
                        'catatan_second' => $row->catatan_altf,
                        'alergy' => $alergy,
                        'sisa_hari' => 'Expired',
                        'day_left' => 'Expired',
                        'date_before' => $riwayat,
                        'date_next' => $next,
                    );
                }
            }else{
                if ($d != 0){
                    $arr[] = array(
                        'id' => $row->id,
                        'customers_id' => $row->customers_id,
                        'periode' => $row->periode,
                        'packages_id' => $row->packages_id,
                        'vouchers_code' => $row->vouchers_code,
                        'payment_method' => $row->payment_method,
                        'tgl_mulai' => $row->tgl_mulai,
                        'protein' => $row->protein,
                        'protein_alternative' => $row->protein_alternative,
                        'carbo' => json_decode($row->carbo,true),
                        'carbo_alternative' => $row->carbo_alternative,
                        'day_off' => "",
                        'address_book_id' => $row->address_book_id,
                        'drivers_id' => $row->drivers_id,
                        'status_berlangganan' => $row->status_berlangganan,
                        'status_payment' => $row->status_payment,
                        'payment_date' => $row->payment_date,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                        'address' => $row->address,
                        'detail_address' => $row->detail_address,
                        'latitude' => $row->latitude,
                        'longitude' => $row->longitude,
                        'nama_penerima' => $row->nama_penerima,
                        'no_penerima' => $row->no_penerima,
                        'no_order' => $row->no_order,
                        'price' => $row->price,
                        'total' => $row->total,
                        'address_name_second' => $row->address_name_second,
                        'address_second' => $row->address_second,
                        'detail_address_second' => $row->detail_address_second,
                        'latitude_second' => $row->latitude_second,
                        'longitude_second' => $row->longitude_second,
                        'nama_penerima_second' => $row->nama_penerima_second,
                        'no_penerima_second' => $row->no_penerima_second,
                        'drivers_id_second' => $row->drivers_id_second,
                        'must_end' => $row->must_end,
                        'day_for' => $row->day_for,
                        'day_for_altf' => $row->day_for_altf,
                        'catatan' => $row->catatan,
                        'catatan_altf' => $row->catatan_altf,
                        'photo_pengiriman' => $row->photo_pengiriman,
                        'nama_penerima_pesanan' => $row->nama_penerima_pesanan,
                        'catatan_driver' => $row->catatan_driver,
                        'is_paused' => $row->is_paused,
                        'date_payment' => $row->date_payment,
                        'no_rek' => $row->no_rek,
                        'nama_rek' => $row->nama_rek,
                        'photo_payment' => $row->photo_payment,
                        'type_apps' => $row->type_apps,
                        'driver_name' => $row->driver_name,
                        'package_name' => $row->package_name,
                        'photo' => $row->photo,
                        'total_send' => $ttl,
                        'day_for_second' => $row->day_for_altf,
                        'catatan_second' => $row->catatan_altf,
                        'alergy' => $alergy,
                        'sisa_hari' => $d,
                        'day_left' => $d,
                        'date_before' => $riwayat,
                        'date_next' => $next,
                    );
                }
            }
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $arr;

        return response()->json($result);
    }
    public function postUpdateOrderDate(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'order_id' => 'required',
            'date_order' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
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
        $per = TrxOrders::simpleQuery()
            ->where('id',g('order_id'))
            ->first();

        $ttl = TrxOrdersDate::simpleQuery()
            ->where('trx_orders_id',g('order_id'))
            ->where('date','<=',$dates)
            ->count();
        $date = explode(',',g('date_order'));
        $sisa = (int)$per->periode - $ttl;
        $ttl_arr = count($date);
        if(date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
            $result['api_status'] = 0;
            $result['api_message'] = 'Gagal melakukan perubahan hari pengiriman, perubahan maximal jam 8 PM';

            return response()->json($result);
            exit();
        }
        if ($sisa < $ttl_arr) {
            $result['api_status'] = 0;
            $result['api_message'] = 'Maaf hari yang anda pilih terlalu banyak';
            return response()->json($result);
            exit();
        }

        $pengiriman = TrxOrdersDate::simpleQuery()
            ->where('trx_orders_id',g('order_id'))
            ->where('date','>',$dates)
            ->delete();

        foreach ($date as $row){
            $save[] = array(
                'trx_orders_id' => g('order_id'),
                'date' => $row,
                'created_at' => date('Y-m-d H:i:s')
            );
        }
        DB::table('trx_orders_date')->insert($save);

        $last = DB::table('trx_orders_date')->where('trx_orders_id', g('order_id'))->orderBy('date','desc')->first();
        $first = DB::table('trx_orders_date')->where('trx_orders_id', g('order_id'))->orderBy('date','asc')->first();

        $order = TrxOrders::findById(g('order_id'));
        $order->setTglMulai($first->date);
        $order->setMustEnd($last->date);
        $order->save();

        $result['api_status'] = 1;
        $result['api_message'] = 'Berhasil memperbarui tanggal pengiriman';
        return response()->json($result);
    }
    public function getQuestion(){
        $list = MsQuestion::simpleQuery()->get();

        foreach ($list as $y){
            if ($y->type_question != 'date picker' AND $y->type_question != 'form' AND $y->type_question != 'forms' AND $y->type_question != 'multiple choices with answer hint'){
                $coise = json_decode($y->choose);
                $rr = null;
                foreach ($coise as $c){
                    $a = explode('|',$c);
                    if (!empty($a[1])){
                        $hint = $a[1];
                    }else{
                        $hint = '';
                    }
                    $rr[] = array(
                        'choice' => $a[0],
                        'additional_hint' => $hint,
                    );
                }
            }elseif ($y->type_question == 'forms'){
                $cc = json_decode($y->choose,true);
                $rr = null;
                foreach ($cc as $c){
                    $a = explode('|',$c);
                    $rr[] = array(
                        'placeholder' => $a[0],
                        'unit' => $a[1],
                    );
                }
            }elseif ($y->type_question == 'multiple choices with answer hint'){
                $cc = json_decode($y->choose,true);
                $rr = null;
                foreach ($cc as $c){
                    $a = explode('|',$c);
                    $rr[] = array(
                        'choice' => $a[0],
                        'hint' => $a[1],
                    );
                }
            }else{
                $rr = $y->choose;
            }

            if ($y->type_question == 'date picker'){
                $arr[] = array(
                    'question' => $y->name,
                    'slug' => $y->slug,
                    'hint' => $y->content,
                    'type' => $y->type_question,
                    'choices' => date('Y-m-d'),
                    'showNextButton' => $y->show_button_next,
                );
            }else{
                $arr[] = array(
                    'question' => $y->name,
                    'slug' => $y->slug,
                    'hint' => $y->content,
                    'type' => $y->type_question,
                    'choices' => $rr,
                    'showNextButton' => $y->show_button_next,
                );
            }
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'Question';
        $result['data'] = $arr;
        return response()->json($result);
    }
    public function postEditQuestion(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $list = MsQuestion::simpleQuery()->get();
        $is_ready = $check = DashboardCustomers::simpleQuery()
            ->where('customers_id',g('id_customer'))
            ->count();
        if($is_ready == 0){
            $result['api_status'] = 0;
            $result['api_message'] = 'Please fill question first';
            return response()->json($result);
        }
        foreach ($list as $y){
            $check = DashboardCustomers::simpleQuery()
                ->where('customers_id',g('id_customer'))
                ->where('slug',$y->slug)
                ->first();

            if ($y->type_question != 'date picker' AND $y->type_question != 'form' AND $y->type_question != 'forms' AND $y->type_question != 'multiple choices with answer hint'){
                $coise = json_decode($y->choose);
                $rr = null;
                foreach ($coise as $c){
                    $a = explode('|',$c);
                    if (!empty($a[1])){
                        $hint = $a[1];
                    }else{
                        $hint = '';
                    }
                    if ($check->answer == $a[0]){
                        $is_answer = true;
                    }else{
                        $is_answer = false;
                    }
                    $rr[] = array(
                        'choice' => $a[0],
                        'additional_hint' => $hint,
                        'is_choosen' => $is_answer,
                    );
                }
            }elseif ($y->type_question == 'forms'){
                $cc = json_decode($y->choose,true);
                $rr = null;
                $answer = explode('|',$check->answer);
                foreach ($cc as $c){
                    $a = explode('|',$c);
                    if ($a[0] == 'Tinggi badan'){
                        $rr[] = array(
                            'placeholder' => $a[0],
                            'unit' => $a[1],
                            'value' => $answer[0],
                        );
                    }else{
                        $rr[] = array(
                            'placeholder' => $a[0],
                            'unit' => $a[1],
                            'value' => $answer[1],
                        );
                    }
                }
            }elseif ($y->type_question == 'multiple choices with answer hint'){
                $cc = json_decode($y->choose,true);
                $rr = null;
                foreach ($cc as $c){
                    $a = explode('|',$c);
                    if ($check->answer == $a[0]){
                        $is_answer = true;
                    }else{
                        $is_answer = false;
                    }
                    $rr[] = array(
                        'choice' => $a[0],
                        'hint' => $a[1],
                        'is_choosen' => $is_answer,
                    );
                }
            }else{
                $rr = $y->choose;
            }

            if ($y->type_question == 'date picker'){
                $arr[] = array(
                    'question' => $y->name,
                    'slug' => $y->slug,
                    'hint' => $y->content,
                    'type' => $y->type_question,
                    'choices' => $check->answer,
                    'showNextButton' => $y->show_button_next,
                );
            }else{
                $arr[] = array(
                    'question' => $y->name,
                    'slug' => $y->slug,
                    'hint' => $y->content,
                    'type' => $y->type_question,
                    'choices' => $rr,
                    'showNextButton' => $y->show_button_next,
                );
            }
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'Question';
        $result['data'] = $arr;
        return response()->json($result);
    }
    public function postDashboard(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $is_ready = $check = DashboardCustomers::simpleQuery()
            ->where('customers_id',g('id_customer'))
            ->count();
        if($is_ready == 0){
            $result['api_status'] = 0;
            $result['api_message'] = 'Please fill question first';
            return response()->json($result);
        }

        $site = asset('');
        $body_1 = DailyFat::simpleQuery()
            ->orderBy('date','asc')
            ->where('customer_id',g('id_customer'))
            ->select('id','date','kilogram as kg',
                DB::raw("concat('$site',photo) as photo")
            )
            ->first();

        $body_2 = DailyFat::simpleQuery()
            ->orderBy('date','desc')
            ->where('customer_id',g('id_customer'))
            ->select('id','date','kilogram as kg',
                DB::raw("concat('$site',photo) as photo")
            )
            ->first();

        $a = DashboardCustomersRepository::GetValue('ibk',g('id_customer'));
        $a = explode('|',$a);
        $usia = DashboardCustomersRepository::GetValue('ktlk',g('id_customer'));
        $usia = date('Y',strtotime($usia));
        $usia = (int)date('Y') - $usia;

        $gender = DashboardCustomersRepository::GetValue('ajkk',g('id_customer'));
        if ($gender == 'Laki-laki'){
            $bmr = 10*$a[1]+6.25*$a[0]-5*$usia+5;
            $bma = (50+(2.3*(($a[1]-152.4)/2.54)));
        }else{
            $bmr = 10*$a[1]+6.25*$a[0]-5*$usia-161;
            $bma = (45.5+(2.3*(($a[1]-152.4)/2.54)));
        }
        $tujuan = DashboardCustomersRepository::GetValue('atdk',g('id_customer'));
        $aktif = DashboardCustomersRepository::GetValue('pkdk',g('id_customer'));
        $tingkat = DashboardCustomersRepository::GetValue('pkdk',g('id_customer'));

        if ($tingkat == 'Jarang aktif'){
            $a_aktif = 1.2;
        }elseif ($tingkat == 'Sedikit aktif'){
            $a_aktif = 1.375;
        }elseif ($tingkat == 'Aktif'){
            $a_aktif = 1.55;
        }else{
            $a_aktif = 1.725;
        }
        $tdee = $bmr*$a_aktif;
        if ($tujuan == 'Hidup sehat'){
            $bb = $tdee;
        }elseif ($tujuan == 'Menaikan berat badan'){
            if ($aktif == 'Lambat'){
                $bb = $tdee+($tdee*15/100);
            }elseif($aktif == 'Sedang'){
                $bb = $tdee+($tdee*22.5/100);
            }else{
                $bb = $tdee+($tdee*30/100);
            }
        }else{
            if ($aktif == 'Lambat'){
                $bb = $tdee-($tdee*15/100);
            }elseif($aktif == 'Sedang'){
                $bb = $tdee-($tdee*22.5/100);
            }else{
                $bb = $tdee-($tdee*30/100);
            }
        }


        $calorys = CaloriesIn::simpleQuery()->whereDate('created_at',date('Y-m-d'))->where('customer_id',g('id_customer'))->get();
        $bb = round($bb);
        $calory_now = 0;
        $carb = 0;
        $protein = 0;
        $fat =0;
        //carb, pro and fat
        foreach ($calorys as $r){
            $calory_now += $r->calory;
            $detail = json_decode($r->detail,true);
            if ($r->type_update == 'product'){
                if ($r->type_in == 'sarving'){
                    $carb += $detail['serving']['carbo'];
                    $protein += $detail['serving']['protein'];
                    $fat += $detail['serving']['lemak'];
                }else{
                    $carb += $detail['ml']['carbo'];
                    $protein += $detail['ml']['protein'];
                    $fat += $detail['ml']['lemak'];
                }
            }elseif($r->type_update == 'catering'){
                $carb += $detail[0]['carbo'];
                $protein += $detail[0]['protein'];
                $fat += $detail[0]['fat'];
            }
        }



        $data['bmi'] = round($a[1]/($a[0]/100*$a[0]/100));
        $data['bmr'] = $bmr;
        $data['gender'] = $gender;
        $data['tinggi'] = $a[0];
        $data['berat'] = $a[1];
        $data['usia'] = $usia;
        $data['tujuan'] = $tujuan;
        $data['aktif'] = $aktif;
        $data['kalori_saat_ini'] = (float)$calory_now;
        $data['kalori_lagi'] = $bb - $calory_now;
        $data['target_kalori'] = $bb;
        $data['carb'] = $carb;
        $data['carb_per'] = 40;
        $data['protein'] = $protein;
        $data['protein_per'] = 40;
        $data['fat'] = $fat;
        $data['fat_per'] = 20;
        $get_kalori = CaloriesIn::simpleQuery()
            ->where('customer_id',g('id_customer'))
            ->whereDate('created_at',date('Y-m-d'))
            ->get();
        $data['kalori_masuk'] = $get_kalori;

        foreach ($get_kalori as $l){
            $l->detail = json_decode($l->detail);
        }
        $data['body_progress_awal'] = $body_1;
        $data['body_progress_akhir'] = $body_2;

        $data['diagram'] = DailyFat::simpleQuery()
            ->orderBy('date','asc')
            ->where('customer_id',g('id_customer'))
            ->select('id','date','kilogram as kg')
            ->get();

        $result['api_status'] = 1;
        $result['api_message'] = 'Dashboard';
        $result['data'] =$data;
        return response()->json($result);
    }
    public function postAnswerQuestion(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'answer' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }
        $jsonnya = str_replace("&amp",'"',g('answer'));
        $jsonnya = str_replace(";#34;","",$jsonnya);
        $json = json_decode($jsonnya,true);
        foreach ($json as $r){
            $simpan[] = array(
                'customers_id' => g('id_customer'),
                'slug' => $r['slug'],
                'answer' => $r['choice'],
                'created_at'=>date('Y-m-d H:i:s'),
            );
        }

        DashboardCustomers::simpleQuery()->where('customers_id',g('id_customer'))->delete();
        if ($simpan){
            DashboardCustomers::simpleQuery()->insert($simpan);
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'Berhasil mengirim jawaban';
        return response()->json($result);
    }
    public function postRiwayatKalori(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

    }
    public function postUpdateBeratBadan(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'kilogram' => 'required',
            'photo' => '',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        $check = DailyFat::simpleQuery()
            ->where('date',date('Y-m-d'))
            ->where('customer_id',g('id_customer'))
            ->first();

        if (!empty($check)){
            $new = DailyFat::findById($check->id);
        }else{
            $new = new DailyFat();
        }
        $new->setCustomerId(g('id_customer'));
        $new->setKilogram(g('kilogram'));
        $new->setDate(date('Y-m-d'));
        $photo = '';
        if (!empty(g('photo'))){
            $photo = CB()->uploadFile('photo',true);
            $new->setPhoto($photo);
            $photo = asset($photo);
        }
        $new->save();

        $data = array(
            'kilogram' => g('kilogram'),
            'photo' => $photo,
        );

        $result['api_status'] = 1;
        $result['api_message'] = 'Success Create data';
        $result['data'] =$data;
        return response()->json($result);
    }
    public function getHoliday(){
        $list = Holidays::all();

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $list;

        return response()->json($result);
    }

    public function getCatering(){
        $site = asset('');
        $list = Menus::simpleQuery()
        ->select(
            'id','name','menu_date',
            DB::raw("concat('$site',menus.photo) as photo"),
            'protein','carbo','calory','fat','gula','saturated_fat','product_id as type_product'
        )
        ->where('menu_date',date('Y-m-d'))->get();

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $list;

        return response()->json($result);
    }

    public function getProduct(){
        $list = ProductCategory::simpleQuery()
            ->select('id','name')
        ->get();
        foreach ($list as $y){
            $product = MsProduct::simpleQuery()
                ->where('product_category_id',$y->id)
                ->get();
            $arr = [];
            foreach ($product as $item) {
                $gr['calory'] = $item->calory;
                $gr['carbo'] = $item->carbo;
                $gr['sugar'] = $item->sugar;
                $gr['protein'] = $item->protein;
                $gr['lemak'] = $item->lemak;

                $ml['calory'] = $item->calory/$item->ukuran_satuan;
                $ml['carbo'] = round($item->carbo/$item->ukuran_satuan,3);
                $ml['sugar'] = $item->sugar/$item->ukuran_satuan;
                $ml['protein'] = $item->protein/$item->ukuran_satuan;
                $ml['lemak'] = $item->lemak/$item->ukuran_satuan;
                $arr[] = array(
                    'id' => $item->id,
                    'name' => $item->name,
                    'photo' => 'https://awsimages.detik.net.id/visual/2019/07/09/5eb5d75b-7eae-4e9c-8a94-1b3a536891ec_169.jpeg?w=650',
                    'serving' => $gr,
                    'ml' => $ml,
                );
            }

            $count = MsProduct::simpleQuery()
                ->where('product_category_id',$y->id)
                ->count();

            $y->total_product = $count;
            $y->list = $arr;
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'success';
        $result['data'] = $list;

        return response()->json($result);
    }

    public function postCaloryIn(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        $type_update = g('type_update');
        $customer_id = g('id_customer');
        $detail = str_replace("&amp",'"',g('detail'));
        $detail = str_replace(";#34;",'',$detail);
        $detail = json_decode($detail);

        if (g('type_update') == 'catering'){
            $set = new CaloriesIn();
            $set->setTypeUpdate($type_update);
            $set->setCustomerId($customer_id);
            $set->setDetail(json_encode($detail));
            $set->setName($detail[0]->name);
            $set->setCalory($detail[0]->calory);
            $set->setCreatedAt(date('Y-m-d H:i:s'));
            $set->save();
        }else{
            foreach ($detail as $r){
                $save[] = array(
                    'type_update' => $type_update,
                    'customer_id' => $customer_id,
                    'detail' => json_encode($r->detail),
                    'name' => $r->detail->name,
                    'calory' => $r->calory_in,
                    'created_at' => date('Y-m-d H:i:s'),
                    'type_in' => $r->type_in
                );
            }
            if($save){
                CaloriesIn::simpleQuery()->insert($save);
            }
        }

        $result['api_status'] = 1;
        $result['api_message'] = 'Success Create data';
        return response()->json($result);
    }



    public function postDeleteCaloryIn(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'id_calory_in' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        $cal = CaloriesIn::simpleQuery()
        ->where('id',g('id_cqlory_in'))
        ->delete();

        $result['api_status'] = 1;
        $result['api_message'] = 'Success delete data';
        return response()->json($result);
    }

    public function postEditCategoriIn(){
        $validator = Validator::make(requestAll(), [
            'id_customer' => 'required',
            'id_calory_in' => 'required',
            'calori_in' => 'required',
            'type_in' => 'required',
            'type_update' => 'required',
        ]);
        if ($validator->fails()) {
            $result['api_status'] = 0;
            $result['api_message'] = implode(', ',$validator->errors()->all());
            return response()->json($result);
            exit();
        }

        $cal = CaloriesIn::findById(g('id_calory_in'));
        $cal->setCalory(g('calori_in'));
        $cal->setTypeIn(g('type_in'));
        $cal->save();

        $result['api_status'] = 1;
        $result['api_message'] = 'Success edit data';
        return response()->json($result);
    }
}