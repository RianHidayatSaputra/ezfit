<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminMasterAlergyController extends CBController {


    public function cbInit()
    {
        $this->setTable("master_alergy");
        $this->setPermalink("master_alergy");
        $this->setPageTitle("Master Alergy");

        $this->addImage("Photo","photo")->encrypt(true);
        $this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addText("Detail","detail")->strLimit(150)->required(false);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);

    }
}
