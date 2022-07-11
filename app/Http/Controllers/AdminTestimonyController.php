<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminTestimonyController extends CBController {


    public function cbInit()
    {
        $this->setTable("testimony");
        $this->setPermalink("testimony");
        $this->setPageTitle("Testimony");

        $this->addImage("Photo","photo")->encrypt(true);
		$this->addSelectOption("Type Testimony","type_testimony",[
            "Testimony Hasil" => "Testimony Hasil",
            "Testimony Taste" => "Testimony Taste"
        ]);
		$this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addTextarea("Content","content")->strLimit(150);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		

    }
}
