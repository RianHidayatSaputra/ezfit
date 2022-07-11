<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminProductCategoryController extends CBController {


    public function cbInit()
    {
        $this->setTable("product_category");
        $this->setPermalink("product_category");
        $this->setPageTitle("Product Category");

        $this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		

    }
}
