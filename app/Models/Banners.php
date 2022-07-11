<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Banners extends Model
{
    public static $tableName = "banners";

    public static $connection = "mysql";

    
	private $id;
	private $title;
	private $photo;
	private $created_at;
	private $updated_at;
	private $description;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByTitle($value) {
		return static::simpleQuery()->where('title',$value)->get();
	}

	public static function findByTitle($value) {
		return static::findBy('title',$value);
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
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

	public static function findAllByDescription($value) {
		return static::simpleQuery()->where('description',$value)->get();
	}

	public static function findByDescription($value) {
		return static::findBy('description',$value);
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description = $description;
	}


}