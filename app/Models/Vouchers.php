<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Vouchers extends Model
{
    public static $tableName = "vouchers";

    public static $connection = "mysql";

    
	private $id;
	private $code;
	private $nominal;
	private $percentage;
	private $date_start;
	private $date_end;
	private $quota;
	private $used;
	private $created_at;
	private $updated_at;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByCode($value) {
		return static::simpleQuery()->where('code',$value)->get();
	}

	public static function findByCode($value) {
		return static::findBy('code',$value);
	}

	public function getCode() {
		return $this->code;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public static function findAllByNominal($value) {
		return static::simpleQuery()->where('nominal',$value)->get();
	}

	public static function findByNominal($value) {
		return static::findBy('nominal',$value);
	}

	public function getNominal() {
		return $this->nominal;
	}

	public function setNominal($nominal) {
		$this->nominal = $nominal;
	}

	public static function findAllByPercentage($value) {
		return static::simpleQuery()->where('percentage',$value)->get();
	}

	public static function findByPercentage($value) {
		return static::findBy('percentage',$value);
	}

	public function getPercentage() {
		return $this->percentage;
	}

	public function setPercentage($percentage) {
		$this->percentage = $percentage;
	}

	public static function findAllByDateStart($value) {
		return static::simpleQuery()->where('date_start',$value)->get();
	}

	public static function findByDateStart($value) {
		return static::findBy('date_start',$value);
	}

	public function getDateStart() {
		return $this->date_start;
	}

	public function setDateStart($date_start) {
		$this->date_start = $date_start;
	}

	public static function findAllByDateEnd($value) {
		return static::simpleQuery()->where('date_end',$value)->get();
	}

	public static function findByDateEnd($value) {
		return static::findBy('date_end',$value);
	}

	public function getDateEnd() {
		return $this->date_end;
	}

	public function setDateEnd($date_end) {
		$this->date_end = $date_end;
	}

	public static function findAllByQuota($value) {
		return static::simpleQuery()->where('quota',$value)->get();
	}

	public static function findByQuota($value) {
		return static::findBy('quota',$value);
	}

	public function getQuota() {
		return $this->quota;
	}

	public function setQuota($quota) {
		$this->quota = $quota;
	}

	public static function findAllByUsed($value) {
		return static::simpleQuery()->where('used',$value)->get();
	}

	public static function findByUsed($value) {
		return static::findBy('used',$value);
	}

	public function getUsed() {
		return $this->used;
	}

	public function setUsed($used) {
		$this->used = $used;
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