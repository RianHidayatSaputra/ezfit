<?php namespace App\Http\Controllers;

use App\Models\AddressBook;
use function foo\func;
use Illuminate\Support\Facades\DB;
use App\Models\Customers;
use App\Models\Drivers;
use Illuminate\Support\Facades\Hash;
use crocodicstudio\crudbooster\controllers\CBController;
use Carbon\Carbon;

class AdminCustomersController extends CBController {


    public function cbInit()
    {
        $this->setTable("customers");
        $this->setPermalink("customers");
        $this->setPageTitle("Daftar Customer");

        $this->addImage("Photo","photo")->encrypt(true);
        $this->addText("Nama","name")->strLimit(150)->maxLength(255);
        $this->addEmail("Email","email");
        $this->addText("Password","password")->showIndex(false)->showIndex(false);
        $this->addText("Ho Hp","ho_hp")->strLimit(150);
        $this->addText("Gender","gender")->strLimit(150)->showIndex(false);
        $this->addText("Tinggi","tinggi")->showIndex(false)->strLimit(150)->showIndex(false);
        $this->addText("Berat","berat")->showIndex(false)->strLimit(150)->showIndex(false);
        $this->addText("Tgl Lahir","tgl_lahir")->showIndex(false)->strLimit(150)->showIndex(false);
        $this->addText("Type Customer","type_customer")
        ->indexDisplayTransform(function ($row) {
            if ($row != 'mahasiswa'){
                $result = '<label class="label label-primary">Umum</label>';
            }else{
                $result = '<label class="label label-success">Mahasiswa</label>';
            }
            return $result;
        });
        $this->addImage("Photo Kr","photo_krs")->showIndex(false)->encrypt(true)->showIndex(false);
        $this->addImage("Photo Ktm","photo_ktm")->showIndex(false)->encrypt(true)->showIndex(false);
        $this->addText("Status","status")->showIndex(false)->strLimit(150)->maxLength(255);
        $this->addDate("Start Date (Mahasiswa)","start_date")->indexDisplayTransform(function($row){
            if ($row == NULL) {
               return '-';
           }else{
            return Carbon::parse($row)->format('d F Y');
        }
    });
        $this->addDate("End Date (Mahasiswa)","end_date")->indexDisplayTransform(function($row){
            if ($row == NULL) {
               return '-';
           }else{
            return Carbon::parse($row)->format('d F Y');
        }
    });
        $this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
        $this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);

        $this->addIndexActionButton("Import Customer",url('admin/customers/import'),"fa fa-upload","primary");
    }
    public function getAdd(){
        $data['page_title'] = 'Add Customers';
        $data['driver'] = Drivers::all();

        return view('backend.customers.add',$data);
    }
    public function postAddSave(){
        $cust = New Customers();
        $cust->setName(g('name'));
        $cust->setEmail(g('email'));
        $cust->setHoHp(g('no_wa'));
        $cust->setGender(g('jenis_kelamin'));
        $cust->setStartDate(g('start_date'));
        $cust->setEndDate(g('end_date'));
        $cust->setTypeCustomer(g('type_customer'));
        $cust->setTglLahir(g('tgl_lahir'));
        $cust->setBerat(g('berat_badan'));
        $cust->setTinggi(g('tinggi_badan'));
        if (g('photo_ktm')){
            $cust->setPhotoKtm(CB()->uploadFile('photo_ktm',true));
        }
        if (g('photo_krs')){
            $cust->setPhotoKrs(CB()->uploadFile('photo_krs',true));
        }
        $cust->setPassword(Hash::make(g('password')));
        $cust->save();

        $nama_alamat = g('nama_alamat');
        $alamat = g('alamat');
        $lat = g('lat');
        $lng = g('lng');
        $detail_address = g('detail_address');
        $nama_penerima = g('nama_penerima');
        $nomor_telpon = g('nomor_telpon');
        $kurir = g('kurir');

        if (!empty($alamat)){
            foreach ($nama_alamat as $key => $value){
                $arr[] = array(
                    'customers_id' => $cust->getId(),
                    'name' => $value,
                    'address' => $alamat[$key],
                    'detail_address' => $detail_address[$key],
                    'latitude' => $lat[$key],
                    'longitude' => $lng[$key],
                    'catatan' => $nama_penerima[$key],
                    'drivers_id' => $kurir[$key],
                );
            }
            DB::table('address_book')->insert($arr);
        }

        if(g('submit') == 'save'){
            return cb()->redirect(action("AdminCustomersController@getIndex"),'Success submit customer',"success");
        }else{
            return redirect()->back()->with(["message_type"=>'success','message'=>'Success submit customer'])->withInput();
        }

    }
    public function getEdit($id){
        $data['page_title'] = 'Add Customers';
        $data['driver'] = Drivers::all();
        $data['customer'] = Customers::findById($id);
        $data['address'] = DB::table('address_book')
        ->leftjoin('drivers','address_book.drivers_id','=','drivers.id')
        ->where('customers_id',$id)
        ->select('address_book.*','drivers.name as kurir')
        ->get();

        return view('backend.customers.edit',$data);
    }
    public function postEditSave($id){
        $up_pass['name'] = g('name');
        $up_pass['email'] = g('email');
        $up_pass['ho_hp'] = g('no_wa');
        $up_pass['gender'] = g('jenis_kelamin');
        $up_pass['start_date'] = g('start_date');
        $up_pass['end_date'] = g('end_date');

        if (g('photo_ktm')){
            $up_pass['photo_ktm'] = CB()->uploadFile('photo_ktm',true);
        }
        if (g('photo_krs')){
            $up_pass['photo_krs'] = CB()->uploadFile('photo_krs',true);
        }
        if (!empty(g('password'))){

            $up_pass['password'] = Hash::make(g('password'));
            
            DB::table('customers')->where('id',$id)->update($up_pass);
        }
        $nama_alamat = g('nama_alamat');
        $alamat = g('alamat');
        $lat = g('lat');
        $lng = g('lng');
        $detail_address = g('detail_address');
        $nama_penerima = g('nama_penerima');
        $nomor_telpon = g('nomor_telpon');
        $kurir = g('kurir');

        DB::table('address_book')->where('customers_id',$id)->delete();

        if (!empty($alamat)){
            foreach ($alamat as $key => $value){
                $arr[] = array(
                    'customers_id' => $id,
                    'name' => $value,
                    'address' => $alamat[$key],
                    'detail_address' => $detail_address[$key],
                    'latitude' => $lat[$key],
                    'longitude' => $lng[$key],
                    'catatan' => $nama_penerima[$key],
                    'drivers_id' => $kurir[$key],
                );
            }
            DB::table('address_book')->insert($arr);
        }

        return cb()->redirect(action("AdminCustomersController@getIndex"),'Success update customer',"success");
    }
}
