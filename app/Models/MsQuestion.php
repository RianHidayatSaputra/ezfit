<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class MsQuestion extends Model
{
    public static $tableName = "ms_question";

    public static $connection = "mysql";

    
	private $id;
	private $name;
	private $slug;
	private $type_question;
	private $choose;
	private $created_at;
	private $updated_at;
	private $order;
	private $content;
	private $show_button_next;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
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

	public static function findAllByTypeQuestion($value) {
		return static::simpleQuery()->where('type_question',$value)->get();
	}

	public static function findByTypeQuestion($value) {
		return static::findBy('type_question',$value);
	}

	public function getTypeQuestion() {
		return $this->type_question;
	}

	public function setTypeQuestion($type_question) {
		$this->type_question = $type_question;
	}

	public static function findAllByChoose($value) {
		return static::simpleQuery()->where('choose',$value)->get();
	}

	public static function findByChoose($value) {
		return static::findBy('choose',$value);
	}

	public function getChoose() {
		return $this->choose;
	}

	public function setChoose($choose) {
		$this->choose = $choose;
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

	public static function findAllByOrder($value) {
		return static::simpleQuery()->where('order',$value)->get();
	}

	public static function findByOrder($value) {
		return static::findBy('order',$value);
	}

	public function getOrder() {
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
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

	public static function findAllByShowButtonNext($value) {
		return static::simpleQuery()->where('show_button_next',$value)->get();
	}

	public static function findByShowButtonNext($value) {
		return static::findBy('show_button_next',$value);
	}

	public function getShowButtonNext() {
		return $this->show_button_next;
	}

	public function setShowButtonNext($show_button_next) {
		$this->show_button_next = $show_button_next;
	}


}