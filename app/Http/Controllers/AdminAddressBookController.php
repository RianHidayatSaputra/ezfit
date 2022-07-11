<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminAddressBookController extends CBController {


    public function cbInit()
    {
        $this->setTable("address_book");
        $this->setPermalink("address_book");
        $this->setPageTitle("Address Book");

        $this->addSelectTable("Customer","customers_id",["table"=>"customers","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
		$this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addText("Address","address")->strLimit(150)->maxLength(255);
		$this->addText("Latitude","latitude")->showIndex(false)->strLimit(150)->maxLength(255);
		$this->addText("Longitude","longitude")->showIndex(false)->strLimit(150)->maxLength(255);
		$this->addText("Receiver","receiver")->showIndex(false)->strLimit(150)->maxLength(255);
		$this->addSelectTable("Driver","drivers_id",["table"=>"drivers","value_option"=>"id","display_option"=>"name","sql_condition"=>""])->showIndex(false);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		

    }
}
