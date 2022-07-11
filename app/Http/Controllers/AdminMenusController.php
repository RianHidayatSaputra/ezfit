<?php namespace App\Http\Controllers;

use App\Models\MasterAlergy;
use App\Models\Menus;
use App\Models\MsCarbon;
use App\Models\MsProtein;
use App\Models\TypeProduct;
use App\Imports\MenusImport;
use crocodicstudio\crudbooster\controllers\CBController;
use Carbon\Carbon;
use File;
use Excel;
use DB;
use Request;

class AdminMenusController extends CBController {


    public function cbInit()
    {
        $this->setTable("menus");
        $this->setPermalink("menus");
        $this->setPageTitle("Daftar Menu");

        $this->addImage("Photo","photo")->encrypt(true);
        $this->addText("Nama Menu","name")->strLimit(150)->maxLength(255);
        $this->addDate("Type Produk","product_id");
        $this->addText("Disediakan Tanggal","menu_date")->indexDisplayTransform(function ($row) {
            return Carbon::parse($row)->format('D, d F Y');
        });
        $this->addText("Allergen","alergy")
        ->indexDisplayTransform(function ($row) {
            $l = json_decode($row);
            $arr = '';
            foreach ($l as $y){
                $arr .= $y->alergy.',';
            }
            return rtrim($arr, ",");
        });
        $this->addText("Kandungan protein dari","protein_from");
        $this->addText("Kandungan Karbo dari","carbo_from");

    }

    public function getAdd(){
        $data['page_title'] = 'Add Menu';
        $data['type_product'] = TypeProduct::all();
        $data['master_alergy'] = MasterAlergy::all();
        $data['carbon'] = MsCarbon::all();
        $data['protein'] = MsProtein::all();
        return view('backend.menus.add',$data);
    }

    public function postSave(){
        if (g('alergy')){
            foreach (g('alergy') as $row){
                $arr[] = array(
                    'alergy'=>$row,
                );
            }
        }else{
            $arr = [];
        }

        $menu = New Menus();
        $menu->setName(g('name'));
        $menu->setMenuDate(g('date_product'));
        $menu->setAlergy(json_encode($arr));
        $menu->setProtein(g('protein'));
        $menu->setCarbo(g('carbo'));
        $menu->setCalory(g('calory'));
        $menu->setFat(g('fat'));
        $menu->setGula(g('gula'));
        $menu->setSaturatedFat(g('saturated_fat'));
        $menu->setProteinP(g('protein_p'));
        $menu->setCarboP(g('carbo_p'));
        $menu->setCaloryP(g('calory_p'));
        $menu->setFatP(g('fat_p'));
        $menu->setGulaP(g('gula_p'));
        $menu->setSaturatedFatP(g('saturated_fat_p'));
        $menu->setProteinFrom(g('protein_from'));
        $menu->setCarboFrom(g('carbo_from'));
        $menu->setProductId(g('product_id'));
        $menu->setPriceHpp(g('price_hpp'));
        $menu->setPriceHppP(g('price_hpp_p'));
        $menu->setPhoto(CB()->uploadFile('photo',true));
        $menu->setCreatedAt(date('Y-m-d H:i:s'));
        $menu->save();

        if(g('submit') == 'save'){
            return cb()->redirect(action("AdminMenusController@getIndex"),'Success submit menu',"success");
        }else{
            return redirect()->back()->with(["message_type"=>'success','message'=>'Success submit menu']);
        }
    }

    public function getEdit($id){
        $menu = Menus::findById($id);
        $data['menu'] = $menu;
        $data['page_title'] = 'Edit Menu';
        $data['type_product'] = TypeProduct::all();
        $data['master_alergy'] = MasterAlergy::all();
        $data['carbon'] = MsCarbon::all();
        $data['protein'] = MsProtein::all();
        return view('backend.menus.edit',$data);
    }

    public function postEditSave($id){
        if (g('alergy')){
            foreach (g('alergy') as $row){
                $arr[] = array(
                    'alergy'=>$row,
                );
            }
        }else{
            $arr = [];
        }
        $menu = Menus::findById($id);
        $menu->setName(g('name'));
        $menu->setMenuDate(g('date_product'));
        $menu->setAlergy(json_encode($arr));
        $menu->setProtein(g('protein'));
        $menu->setCarbo(g('carbo'));
        $menu->setCalory(g('calory'));
        $menu->setFat(g('fat'));
        $menu->setGula(g('gula'));
        $menu->setSaturatedFat(g('saturated_fat'));
        $menu->setProteinP(g('protein_p'));
        $menu->setCarboP(g('carbo_p'));
        $menu->setCaloryP(g('calory_p'));
        $menu->setFatP(g('fat_p'));
        $menu->setGulaP(g('gula_p'));
        $menu->setSaturatedFatP(g('saturated_fat_p'));
        $menu->setProteinFrom(g('protein_from'));
        $menu->setCarboFrom(g('carbo_from'));
        $menu->setProductId(g('product_id'));
        $menu->setPriceHpp(g('price_hpp'));
        $menu->setPriceHppP(g('price_hpp_p'));
        if(!empty(g('photo'))){
            $menu->setPhoto(CB()->uploadFile('photo',true));
        }
        $menu->save();

        return cb()->redirect(action("AdminMenusController@getIndex"),'Success submit menu',"success");
    }

    public function getDetail($id){
        $data['page_title'] = "Detail Menu";
        $data['row'] = Menus::findById($id);

        return view('backend.menus.detail',$data);
    }

    public function postImport(){
        $extension = File::extension(Request::file('import-file')->getClientOriginalName());
        if($extension == "xlsx" || $extension == "xls" || $extension == "csv"){
            $save = Excel::import(new MenusImport, Request::file('import-file'));

            if($save){
                return cb()->redirect(action("AdminMenusController@getIndex"),'Import Success!',"success");
            }else{
                return cb()->redirect(action("AdminMenusController@getIndex"),'Failed!',"success");
            }
        }else {
            return cb()->redirect(action("AdminMenusController@getIndex"),'Extension is Not Valid!',"success");
        }
    }
}
