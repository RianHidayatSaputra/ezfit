<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminBannersController extends CBController {


    public function cbInit()
    {
        $this->setTable("banners");
        $this->setPermalink("banners");
        $this->setPageTitle("Banners");

        $this->addText("Title","title")->strLimit(150)->maxLength(255);
		$this->addImage("Photo","photo")->encrypt(true);
        $this->addWysiwyg("Description","description")->showIndex(false);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		

    }
}
