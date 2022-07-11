<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;
use App\Models\MasterPackage;

class AdminMasterPackageController extends CBController {


    public function cbInit()
    {
        $this->setButtonAdd(true);
        $this->setButtonEdit(true);
        $this->setTable("master_package");
        $this->setPermalink("master_package");
        $this->setPageTitle("Master Package");

        $this->addText("Periode","periode");
		$this->addNumber("Percen","percen")->required(false)->indexDisplayTransform(function($row){
            if ($row == NULL) {
                return 0;
            }else{
                return $row;
            }
        });
        $this->addSelectOption("Type Package","type_package",[
            "Regular" => "Regular",
            "Propack" => "Propack"
        ]);
    }

    public function getIndex(){
        $data['page_title'] = 'Master Package';
        $data['regular'] = MasterPackage::findAllByTypePackage('regular');
        $data['propack'] = MasterPackage::findAllByTypePackage('propack');

        return view('backend.master_package.index',$data);


    }
}
