<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminInformationController extends CBController {


    public function cbInit()
    {
        $this->setTable("log_notice");
        $this->setPermalink("information");
        $this->setPageTitle("Information");

        $this->addSelectTable("Customer","customers_id",["table"=>"customers","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
		$this->addSelectTable("Order","trx_orders_id",["table"=>"trx_orders","value_option"=>"id","display_option"=>"address_name_second","sql_condition"=>""]);
		$this->addWysiwyg("Content","content")->strLimit(150);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addText("Title","title")->strLimit(150)->maxLength(255);
		$this->addText("Type Notice","type_notice")->strLimit(150)->maxLength(255);
		$this->addText("Type","type")->strLimit(150)->maxLength(255);
		

    }
}
