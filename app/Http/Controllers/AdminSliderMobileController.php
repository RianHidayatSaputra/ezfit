<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminSliderMobileController extends CBController {


    public function cbInit()
    {
        $this->setTable("slider_mobile");
        $this->setPermalink("slider_mobile");
        $this->setPageTitle("Slider Mobile");

        $this->addImage("Photo","photo")->encrypt(true);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		

    }
}
