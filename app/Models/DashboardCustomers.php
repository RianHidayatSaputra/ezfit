<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class DashboardCustomers extends Model
{
    public static $tableName = "dashboard_customers";

    public static $connection = "mysql";

    
	private $id;
	private $customers_id;
	private $slug;
	private $answer;
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

	public static function findAllBySlug($value) {
		return static::simpleQuery()->where('slug',$value)->get();
	}

	public static function findBySlug($value) {
		return static::findBy('slug',$value);
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($slug) {
		$this->slug = $slug;
	}

	public static function findAllByAnswer($value) {
		return static::simpleQuery()->where('answer',$value)->get();
	}

	public static function findByAnswer($value) {
		return static::findBy('answer',$value);
	}

	public function getAnswer() {
		return $this->answer;
	}

	public function setAnswer($answer) {
		$this->answer = $answer;
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