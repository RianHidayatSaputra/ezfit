<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;
use DB;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends CBController {


    public function cbInit()
    {
        $this->setTable("users");
        $this->setPermalink("users");
        $this->setPageTitle("Daftar User");

        $this->addImage("Photo","photo")->encrypt(true);
        $this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addEmail("Email","email");
		$this->addPassword("Password","password")->showIndex(false);
		$this->addSelectTable("Role","cb_roles_id",["table"=>"cb_roles","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
        $this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false)->showIndex(false);
        $this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false)->showIndex(false);

    }
}
