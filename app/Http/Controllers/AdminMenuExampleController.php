<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminMenuExampleController extends CBController {


    public function cbInit()
    {
        $this->setTable("menu_example");
        $this->setPermalink("menu_example");
        $this->setPageTitle("Menu Example");

        $this->addImage("Photo","photo")->encrypt(true);
		$this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addText("Calory","calory")->strLimit(150)->maxLength(255);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		

    }
}
