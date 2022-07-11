<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class ApiLog extends Model
{
    public static $tableName = "api_log";

    public static $connection = "mysql";

    
	private $id;
	private $user_id;
	private $segment;
	private $created_at;
	private $updated_at;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByUserId($value) {
		return static::simpleQuery()->where('user_id',$value)->get();
	}

	public static function findByUserId($value) {
		return static::findBy('user_id',$value);
	}

	public function getUserId() {
		return $this->user_id;
	}

	public function setUserId($user_id) {
		$this->user_id = $user_id;
	}

	public static function findAllBySegment($value) {
		return static::simpleQuery()->where('segment',$value)->get();
	}

	public static function findBySegment($value) {
		return static::findBy('segment',$value);
	}

	public function getSegment() {
		return $this->segment;
	}

	public function setSegment($segment) {
		$this->segment = $segment;
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