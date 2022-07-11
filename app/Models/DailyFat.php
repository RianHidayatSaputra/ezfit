<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class DailyFat extends Model
{
    public static $tableName = "daily_fat";

    public static $connection = "mysql";

    
	private $id;
	private $customer_id;
	private $kilogram;
    private $photo;
    private $date;
	private $created_at;
	private $updated_at;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }
    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
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

	public static function findAllByKilogram($value) {
		return static::simpleQuery()->where('kilogram',$value)->get();
	}

	public static function findByKilogram($value) {
		return static::findBy('kilogram',$value);
	}

	public function getKilogram() {
		return $this->kilogram;
	}

	public function setKilogram($kilogram) {
		$this->kilogram = $kilogram;
	}

	public static function findAllByPhoto($value) {
		return static::simpleQuery()->where('photo',$value)->get();
	}

	public static function findByPhoto($value) {
		return static::findBy('photo',$value);
	}

	public function getPhoto() {
		return $this->photo;
	}

	public function setPhoto($photo) {
		$this->photo = $photo;
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