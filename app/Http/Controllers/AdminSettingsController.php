<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminSettingsController extends CBController {


    public function cbInit()
    {
        $this->setTable("settings");
        $this->setPermalink("settings");
        $this->setPageTitle("Settings");

        $this->addText("Slug","slug")->strLimit(150)->maxLength(255);
        $this->addText("Title","title")->strLimit(150)->maxLength(255);
        if (request()->segment(4) == 4){
            $this->addFile("Description","description")->encrypt(true);
        }else{
            $this->addText("Description","description")->strLimit(150);
        }
        $this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
        $this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
        

    }
}
