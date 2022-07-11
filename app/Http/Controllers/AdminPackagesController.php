<?php namespace App\Http\Controllers;

use App\Models\Packages;
use App\Models\TypeProduct;
use crocodicstudio\crudbooster\controllers\CBController;

class AdminPackagesController extends CBController {

    public function cbInit()
    {
        $this->setTable("packages");
        $this->setPermalink("packages");
        $this->setPageTitle("Package");

        $this->addImage("Photo","photo")->encrypt(true);
        $this->addText("Name","name")->strLimit(150)->maxLength(255);
        $this->addText("Type Package","type_package")
            ->indexDisplayTransform(function ($row) {
                if($row == 'Regular'){
                    $result = '<label class="label label-success">Regular</label>';
                }else{
                    $result = '<label class="label label-warning">Propack</label>';
                }

                return $result;
            });
		$this->addMoney("Price Umum 1 Hari","price_u1");
		$this->addMoney("Price Umum 6 Hari","price_u2");
		$this->addMoney("Price Umum 24 Hari","price_u3");
		$this->addMoney("Price Mahasiswa 1 Hari","price_m1");
		$this->addMoney("Price Mahasiswa 6 Hari","price_m2");
		$this->addMoney("Price Mahasiswa 24 Hari","price_m3");
    }

    public function getAdd(){
        $data['page_title'] = 'Add Package';
        $data['type_product'] = TypeProduct::all();

        return view('backend.package.add',$data);
    }
    public function postAddSave(){
        $cate = g('category');

        foreach($cate as $row){
            $arr[] = array(
              'package'=>$row,
            );
        }
        $pack = New Packages();
        $pack->setName(g('name'));
        $pack->setCategory(json_encode($arr));
        $pack->setTypePackage(g('type_package'));
        $pack->setPriceU1(g('price_u1'));
        $pack->setPriceU2(g('price_u2'));
        $pack->setPriceU3(g('price_u3'));
        $pack->setPriceM1(g('price_m1'));
        $pack->setPriceM2(g('price_m2'));
        $pack->setPriceM3(g('price_m3'));

        $pack->setPriceUh1(g('price_uh1'));
        $pack->setPriceUh2(g('price_uh2'));
        $pack->setPriceUh3(g('price_uh3'));
        $pack->setPriceMh1(g('price_mh1'));
        $pack->setPriceMh2(g('price_mh2'));
        $pack->setPriceMh3(g('price_mh3'));

        $pack->setItemTotal(g('item_total'));
        if (g('photo')){
            $pack->setPhoto(CB()->uploadFile('photo',true));
        }

        $pack->save();

        if(g('submit') == 'save'){
            return cb()->redirect(action("AdminPackagesController@getIndex"),'Success submit',"success");
        }else{
            return redirect()->back()->with(["message_type"=>'success','message'=>'Success submit'])->withInput();
        }
    }
    public function getEdit($id){
        $data['page_title'] = 'Edit Package';
        $data['dt'] = Packages::findById($id);
        $data['type_product'] = TypeProduct::all();

        return view('backend.package.edit',$data);
    }
    public function postEditSave($id){
        $cate = g('category');
        foreach($cate as $row){
            $arr[] = array(
                'package'=>$row,
            );
        }
        $pack = Packages::findById($id);
        $pack->setName(g('name'));
        $pack->setTypePackage(g('type_package'));
        $pack->setCategory(json_encode($arr));

        $pack->setPriceU1(g('price_u1'));
        $pack->setPriceU2(g('price_u2'));
        $pack->setPriceU3(g('price_u3'));
        $pack->setPriceM1(g('price_m1'));
        $pack->setPriceM2(g('price_m2'));
        $pack->setPriceM3(g('price_m3'));

        $pack->setPriceUh1(g('price_uh1'));
        $pack->setPriceUh2(g('price_uh2'));
        $pack->setPriceUh3(g('price_uh3'));
        $pack->setPriceMh1(g('price_mh1'));
        $pack->setPriceMh2(g('price_mh2'));
        $pack->setPriceMh3(g('price_mh3'));

        $pack->setItemTotal(g('item_total'));
        if (g('photo')){
            $pack->setPhoto(CB()->uploadFile('photo',true));
        }

        $pack->save();

        return cb()->redirect(action("AdminPackagesController@getIndex"),'Success Update',"success");

    }
}
