<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminTrxOrdersAlergyController extends CBController {


    public function cbInit()
    {
        $this->setTable("trx_orders_alergy");
        $this->setPermalink("trx_orders_alergy");
        $this->setPageTitle("Trx Orders_alergy");

        $this->addSelectTable("Master Alergy","master_alergy_id",["table"=>"master_alergy","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		

    }
}
