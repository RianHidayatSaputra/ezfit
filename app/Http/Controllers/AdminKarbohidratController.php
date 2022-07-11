<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminKarbohidratController extends CBController {


    public function cbInit()
    {
        $this->setTable("ms_carbon");
        $this->setPermalink("karbohidrat");
        $this->setPageTitle("Karbohidrat");

        $this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		

    }
}
