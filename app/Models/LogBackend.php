<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class LogBackend extends Model
{
    public static $tableName = "log_backend";

    public static $connection = "mysql";

    
	private $id;
	private $customers_id;
	private $trx_orders_id;
	private $content;
	private $is_read;
	private $created_at;
	private $updated_at;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
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

	public static function findAllByTrxOrdersId($value) {
		return static::simpleQuery()->where('trx_orders_id',$value)->get();
	}

	/**
	* @return TrxOrders
	*/
	public function getTrxOrdersId() {
		return TrxOrders::findById($this->trx_orders_id);
	}

	public function setTrxOrdersId($trx_orders_id) {
		$this->trx_orders_id = $trx_orders_id;
	}

	public static function findAllByContent($value) {
		return static::simpleQuery()->where('content',$value)->get();
	}

	public static function findByContent($value) {
		return static::findBy('content',$value);
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}

	public static function findAllByIsRead($value) {
		return static::simpleQuery()->where('is_read',$value)->get();
	}

	public static function findByIsRead($value) {
		return static::findBy('is_read',$value);
	}

	public function getIsRead() {
		return $this->is_read;
	}

	public function setIsRead($is_read) {
		$this->is_read = $is_read;
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


}