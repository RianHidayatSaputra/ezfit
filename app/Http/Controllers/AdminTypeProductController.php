<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminTypeProductController extends CBController {


    public function cbInit()
    {
        $this->setTable("type_product");
        $this->setPermalink("type_product");
        $this->setPageTitle("Type Product");

        $this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addText("Name","name")->strLimit(150)->maxLength(255);
		

    }
}
