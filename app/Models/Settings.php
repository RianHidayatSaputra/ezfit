<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Settings extends Model
{
    public static $tableName = "settings";

    public static $connection = "mysql";

    
	private $id;
	private $slug;
	private $title;
	private $description;
	private $created_at;
	private $updated_at;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
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