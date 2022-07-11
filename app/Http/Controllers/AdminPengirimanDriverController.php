<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;

class AdminPengirimanDriverController extends CBController {


    public function cbInit()
    {
        $this->setTable("trx_orders");
        $this->setPermalink("pengiriman_driver");
        $this->setPageTitle("Pengiriman Driver");

    }

    public function getIndex(){
    	$data['page_title'] = 'Report Pengiriman (Driver)';

    	return view('backend.report.pengiriman', $data);
    }
}
