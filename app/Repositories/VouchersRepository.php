<?php
namespace App\Repositories;

use DB;
use App\Models\Vouchers;

class VouchersRepository extends Vouchers
{
	public static function getDiscountByCode($code,$nominal = null){
		$type = self::getTypeByCode($code);
		$find = Vouchers::findByCode($code);
		if ($type == 'percentage') {
			$percentage = $find->getPercentage();
			$result = $nominal * $percentage / 100;
		}else{
			$result = $find->getNominal();
		}

		return $result;
	}

	public static function getTypeByCode($code){
		$check = static::simpleQuery()
		->where('code',$code)
		->whereNotNull('percentage')
		->first();

		if ($check) {
			$result = 'percentage';
		}else{
			$result = 'nominal';
		}

		return $result;
	}

	public static function subQuota($code){
		$action = Vouchers::findByCode($code);
		$action->setQuota($action->getQuota() - 1);

		if ($action->getUsed() == NULL) {
			$used = 0;
		}else{
			$used = $action->getUsed();
		}

		$action->setUsed($used + 1);
		$action->save();
	}

	public static function addQuota($code){
		$action = Vouchers::findByCode($code);
		$action->setQuota($action->getQuota() + 1);
		$action->setUsed($action->getUsed() - 1);
		$action->save();
	}

	public static function checkQuota($id){
		$q = Vouchers::findById($id);

		if ($q->getUsed() == NULL) {
			$used = 0;
		}else{
			$used = $q->getUsed();
		}

		$remaining = $q->getQuota();
		$result = $remaining + $used;

		return $result;
	}

}