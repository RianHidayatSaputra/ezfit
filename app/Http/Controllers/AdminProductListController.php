<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminProductListController extends CBController {


    public function cbInit()
    {
        $this->setTable("ms_product");
        $this->setPermalink("product_list");
        $this->setPageTitle("Product List");

        $this->addSelectTable("Product Category","product_category_id",["table"=>"product_category","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
		$this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addWysiwyg("Detail Per Pc","detail_per_pcs")->strLimit(150);
		$this->addText("Calory","calory")->strLimit(150)->maxLength(255);
		$this->addText("Carb","carb")->strLimit(150)->maxLength(255);
		$this->addText("Protein","protein")->strLimit(150)->maxLength(255);
		$this->addText("Fat","fat")->strLimit(150)->maxLength(255);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		

    }
}
