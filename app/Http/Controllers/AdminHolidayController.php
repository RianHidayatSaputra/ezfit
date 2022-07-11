<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminHolidayController extends CBController {


    public function cbInit()
    {
        $this->setTable("holidays");
        $this->setPermalink("holiday");
        $this->setPageTitle("Holiday");

        $this->addDate("Date","date");
		$this->addText("Content","content")->strLimit(150);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		

    }
}
