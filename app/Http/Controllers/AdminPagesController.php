<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminPagesController extends CBController {


    public function cbInit()
    {
        $this->setTable("pages");
        $this->setPermalink("pages");
        $this->setPageTitle("Pages");

        $this->addText("Slug","slug")->showIndex(false);
		$this->addText("Title","title")->strLimit(150)->maxLength(255);
		$this->addWysiwyg("Content","content")->strLimit(150);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		

    }
}
