<?php namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;
use Carbon\Carbon;
use App\Repositories\VouchersRepository;

class AdminVouchersController extends CBController {


	public function cbInit()
	{
		$this->setTable("vouchers");
		$this->setPermalink("vouchers");
		$this->setPageTitle("Vouchers");

		$this->addText("Code","code")->strLimit(150)->maxLength(255);
		$this->addNumber("Nominal","nominal")->required(false)->required(false)->indexDisplayTransform(function ($row) {
			if($row == NULL){
				return '-';
			}else{
				return 'Rp.'.number_format($row);
			}
		});
        $this->addSelectOption("Voucher Type","type_voucher",[
            "semua" => "Semua Kalangan",
            "umum" => "Umum",
            "mahasiswa" => "Mahasiswa",
        ]);
		$this->addNumber("Percentage","percentage")->required(false)->indexDisplayTransform(function ($row) {
			if($row == NULL){
				return '-';
			}else{
				return $row.'%';
			}
		});
		$this->addDate("Date Start","date_start")->indexDisplayTransform(function ($row) {
			return Carbon::parse($row)->format('j F Y');
		});
		$this->addDate("Date End","date_end")->indexDisplayTransform(function ($row) {
			return Carbon::parse($row)->format('j F Y');
		});
		$this->addNumber("Remaining","quota")->indexDisplayTransform(function ($row) {
			if ($row == NULL) {
				return 0;
			}else{
				return number_format($row);
			}
		});;
		$this->addNumber("Used","used")->required(false)->showAdd(false)->showEdit(false)->indexDisplayTransform(function ($row) {
			if ($row == NULL) {
				return 0;
			}else{
				return number_format($row);
			}
		});
        $this->addNumber("Used","used")->showAdd(true)->showEdit(true)->showIndex(false)->showDetail(true);
		$this->addNumber("Quota","id")->required(false)->showAdd(false)->showEdit(false)->indexDisplayTransform(function ($row) {
			return number_format(VouchersRepository::checkQuota($row));
		});
		$this->addDatetime("Created at","created_at")->required(false)->showAdd(false)->showEdit(false)->indexDisplayTransform(function ($row) {
			return Carbon::parse($row)->format('d-M-Y H:i');
		});
		$this->addDatetime("Updated at","updated_at")->required(false)->showAdd(false)->showEdit(false)->indexDisplayTransform(function ($row) {
			return Carbon::parse($row)->format('d-M-Y H:i');
		});
		

	}
}
