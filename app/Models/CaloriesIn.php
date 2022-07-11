<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class CaloriesIn extends Model
{
    public static $tableName = "calories_in";

    public static $connection = "mysql";

    
	private $id;
	private $type_update;
	private $name;
	private $detail;
	private $calory;
	private $customer_id;
	private $created_at;
	private $updated_at;
	private $type_in;

    /**
     * @return mixed
     */
    public function getTypeIn()
    {
        return $this->type_in;
    }

    /**
     * @param mixed $type_in
     */
    public function setTypeIn($type_in): void
    {
        $this->type_in = $type_in;
    }
    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByTypeUpdate($value) {
		return static::simpleQuery()->where('type_update',$value)->get();
	}

	public static function findByTypeUpdate($value) {
		return static::findBy('type_update',$value);
	}

	public function getTypeUpdate() {
		return $this->type_update;
	}

	public function setTypeUpdate($type_update) {
		$this->type_update = $type_update;
	}

	public static function findAllByName($value) {
		return static::simpleQuery()->where('name',$value)->get();
	}

	public static function findByName($value) {
		return static::findBy('name',$value);
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public static function findAllByDetail($value) {
		return static::simpleQuery()->where('detail',$value)->get();
	}

	public static function findByDetail($value) {
		return static::findBy('detail',$value);
	}

	public function getDetail() {
		return $this->detail;
	}

	public function setDetail($detail) {
		$this->detail = $detail;
	}

	public static function findAllByCalory($value) {
		return static::simpleQuery()->where('calory',$value)->get();
	}

	public static function findByCalory($value) {
		return static::findBy('calory',$value);
	}

	public function getCalory() {
		return $this->calory;
	}

	public function setCalory($calory) {
		$this->calory = $calory;
	}

	public static function findAllByCustomerId($value) {
		return static::simpleQuery()->where('customer_id',$value)->get();
	}

	public static function findByCustomerId($value) {
		return static::findBy('customer_id',$value);
	}

	public function getCustomerId() {
		return $this->customer_id;
	}

	public function setCustomerId($customer_id) {
		$this->customer_id = $customer_id;
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