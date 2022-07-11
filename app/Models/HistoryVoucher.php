<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class HistoryVoucher extends Model
{
    public static $tableName = "history_voucher";

    public static $connection = "mysql";

    
	private $id;
	private $created_at;
	private $updated_at;
	private $vouchers_id;
	private $customers_id;
	private $date;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByCreatedAt($value) {
		return static::simpleQuery()->where('created_at',$value)->get();
	}

	public static function findByCreatedAt($value) {
		return static::findBy('created_at',$value);
	}

	public function getCreatedAt() {
		return $this->created_at;
	}

	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}

	public static function findAllByUpdatedAt($value) {
		return static::simpleQuery()->where('updated_at',$value)->get();
	}

	public static function findByUpdatedAt($value) {
		return static::findBy('updated_at',$value);
	}

	public function getUpdatedAt() {
		return $this->updated_at;
	}

	public function setUpdatedAt($updated_at) {
		$this->updated_at = $updated_at;
	}

	public static function findAllByVouchersId($value) {
		return static::simpleQuery()->where('vouchers_id',$value)->get();
	}

	/**
	* @return Vouchers
	*/
	public function getVouchersId() {
		return Vouchers::findById($this->vouchers_id);
	}

	public function setVouchersId($vouchers_id) {
		$this->vouchers_id = $vouchers_id;
	}

	public static function findAllByCustomersId($value) {
		return static::simpleQuery()->where('customers_id',$value)->get();
	}

	/**
	* @return Customers
	*/
	public function getCustomersId() {
		return Customers::findById($this->customers_id);
	}

	public function setCustomersId($customers_id) {
		$this->customers_id = $customers_id;
	}

	public static function findAllByDate($value) {
		return static::simpleQuery()->where('date',$value)->get();
	}

	public static function findByDate($value) {
		return static::findBy('date',$value);
	}

	public function getDate() {
		return $this->date;
	}

	public function setDate($date) {
		$this->date = $date;
	}


}