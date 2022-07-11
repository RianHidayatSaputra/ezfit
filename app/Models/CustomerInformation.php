<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class CustomerInformation extends Model
{
    public static $tableName = "customer_information";

    public static $connection = "mysql";

    
	private $id;
	private $gender;
	private $height;
	private $weight;
	private $old;
	private $tujuan;
	private $activity;
	private $diet_speed;
	private $created_at;
	private $updated_at;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByGender($value) {
		return static::simpleQuery()->where('gender',$value)->get();
	}

	public static function findByGender($value) {
		return static::findBy('gender',$value);
	}

	public function getGender() {
		return $this->gender;
	}

	public function setGender($gender) {
		$this->gender = $gender;
	}

	public static function findAllByHeight($value) {
		return static::simpleQuery()->where('height',$value)->get();
	}

	public static function findByHeight($value) {
		return static::findBy('height',$value);
	}

	public function getHeight() {
		return $this->height;
	}

	public function setHeight($height) {
		$this->height = $height;
	}

	public static function findAllByWeight($value) {
		return static::simpleQuery()->where('weight',$value)->get();
	}

	public static function findByWeight($value) {
		return static::findBy('weight',$value);
	}

	public function getWeight() {
		return $this->weight;
	}

	public function setWeight($weight) {
		$this->weight = $weight;
	}

	public static function findAllByOld($value) {
		return static::simpleQuery()->where('old',$value)->get();
	}

	public static function findByOld($value) {
		return static::findBy('old',$value);
	}

	public function getOld() {
		return $this->old;
	}

	public function setOld($old) {
		$this->old = $old;
	}

	public static function findAllByTujuan($value) {
		return static::simpleQuery()->where('tujuan',$value)->get();
	}

	public static function findByTujuan($value) {
		return static::findBy('tujuan',$value);
	}

	public function getTujuan() {
		return $this->tujuan;
	}

	public function setTujuan($tujuan) {
		$this->tujuan = $tujuan;
	}

	public static function findAllByActivity($value) {
		return static::simpleQuery()->where('activity',$value)->get();
	}

	public static function findByActivity($value) {
		return static::findBy('activity',$value);
	}

	public function getActivity() {
		return $this->activity;
	}

	public function setActivity($activity) {
		$this->activity = $activity;
	}

	public static function findAllByDietSpeed($value) {
		return static::simpleQuery()->where('diet_speed',$value)->get();
	}

	public static function findByDietSpeed($value) {
		return static::findBy('diet_speed',$value);
	}

	public function getDietSpeed() {
		return $this->diet_speed;
	}

	public function setDietSpeed($diet_speed) {
		$this->diet_speed = $diet_speed;
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