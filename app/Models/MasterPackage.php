<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class MasterPackage extends Model
{
    public static $tableName = "master_package";

    public static $connection = "mysql";

    
	private $id;
	private $periode;
	private $percen;
	private $created_at;
	private $updated_at;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByPeriode($value) {
		return static::simpleQuery()->where('periode',$value)->get();
	}

	public static function findByPeriode($value) {
		return static::findBy('periode',$value);
	}

	public function getPeriode() {
		return $this->periode;
	}

	public function setPeriode($periode) {
		$this->periode = $periode;
	}

	public static function findAllByPercen($value) {
		return static::simpleQuery()->where('percen',$value)->get();
	}

	public static function findByPercen($value) {
		return static::findBy('percen',$value);
	}

	public function getPercen() {
		return $this->percen;
	}

	public function setPercen($percen) {
		$this->percen = $percen;
	}

	public static function findAllByTypePackage($value) {
		return static::simpleQuery()->where('type_package',$value)->get();
	}

	public static function findByTypePackage($value) {
		return static::findBy('type_package',$value);
	}

	public function getTypePackage() {
		return $this->type_package;
	}

	public function setTypePackage($type_package) {
		$this->type_package = $type_package;
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