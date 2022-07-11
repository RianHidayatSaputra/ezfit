<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Drivers;
use crocodicstudio\crudbooster\controllers\CBController;
use Illuminate\Support\Facades\Hash;

class AdminDriversController extends CBController {


    public function cbInit()
    {
        $this->setTable("drivers");
        $this->setPermalink("drivers");
        $this->setPageTitle("Daftar Driver");

        $this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addImage("Photo","photo")->encrypt(true);
		$this->addText("Kode","kode")->strLimit(150)->maxLength(255);
		$this->addText("No Wa","no_wa")->strLimit(150)->maxLength(255);
		$this->addEmail("Email","email");
		$this->addPassword("Password","password")->showIndex(false);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);


        $this->hookAfterInsert(function($last_insert_id) {
            // Todo: code here
            $find = Drivers::findById($last_insert_id);

            $new['cb_roles_id'] = 3;
            $new['name'] = $find->getName();
            $new['photo'] = $find->getPhoto();
            $new['password'] = $find->getPassword();
            $new['email'] = $find->getEmail();

            DB::table('users')->insert($new);
            $last_id = DB::getPdo()->lastInsertId();

            $find->setUsersId($last_id);
            $find->save();
        });

        $this->hookAfterUpdate(function($id) {
            // Todo: code here
            $find = Drivers::findById($id);
            if ($find->getUsersId()){
                $new['name'] = $find->getName();
                $new['photo'] = $find->getPhoto();
                $new['password'] = $find->getPassword();
                $new['email'] = $find->getEmail();

                DB::table('users')->where('id',$find->getUsersId())->update($new);
            }else{
                $new['cb_roles_id'] = 3;
                $new['name'] = $find->getName();
                $new['photo'] = $find->getPhoto();
                $new['password'] = $find->getPassword();
                $new['email'] = $find->getEmail();

                DB::table('users')->insert($new);
                $last_id = DB::getPdo()->lastInsertId();

                $find->setUsersId($last_id);
                $find->save();
            }

        });

    }
}
