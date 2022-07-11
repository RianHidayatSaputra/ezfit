<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class MsProduct extends Model
{
    public static $tableName = "ms_product";

    public static $connection = "mysql";

    
	private $id;
	private $product_category_id;
	private $name;
	private $serving_ml;
	private $serving_calory;
	private $serving_carb;
	private $serving_protein;
	private $serving_fat;
	private $gr_ml;
	private $gr_calory;
	private $gr_carb;
	private $gr_protein;
	private $gr_fat;
	private $created_at;
	private $updated_at;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByProductCategoryId($value) {
		return static::simpleQuery()->where('product_category_id',$value)->get();
	}

	/**
	* @return ProductCategory
	*/
	public function getProductCategoryId() {
		return ProductCategory::findById($this->product_category_id);
	}

	public function setProductCategoryId($product_category_id) {
		$this->product_category_id = $product_category_id;
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

	public static function findAllByServingMl($value) {
		return static::simpleQuery()->where('serving_ml',$value)->get();
	}

	public static function findByServingMl($value) {
		return static::findBy('serving_ml',$value);
	}

	public function getServingMl() {
		return $this->serving_ml;
	}

	public function setServingMl($serving_ml) {
		$this->serving_ml = $serving_ml;
	}

	public static function findAllByServingCalory($value) {
		return static::simpleQuery()->where('serving_calory',$value)->get();
	}

	public static function findByServingCalory($value) {
		return static::findBy('serving_calory',$value);
	}

	public function getServingCalory() {
		return $this->serving_calory;
	}

	public function setServingCalory($serving_calory) {
		$this->serving_calory = $serving_calory;
	}

	public static function findAllByServingCarb($value) {
		return static::simpleQuery()->where('serving_carb',$value)->get();
	}

	public static function findByServingCarb($value) {
		return static::findBy('serving_carb',$value);
	}

	public function getServingCarb() {
		return $this->serving_carb;
	}

	public function setServingCarb($serving_carb) {
		$this->serving_carb = $serving_carb;
	}

	public static function findAllByServingProtein($value) {
		return static::simpleQuery()->where('serving_protein',$value)->get();
	}

	public static function findByServingProtein($value) {
		return static::findBy('serving_protein',$value);
	}

	public function getServingProtein() {
		return $this->serving_protein;
	}

	public function setServingProtein($serving_protein) {
		$this->serving_protein = $serving_protein;
	}

	public static function findAllByServingFat($value) {
		return static::simpleQuery()->where('serving_fat',$value)->get();
	}

	public static function findByServingFat($value) {
		return static::findBy('serving_fat',$value);
	}

	public function getServingFat() {
		return $this->serving_fat;
	}

	public function setServingFat($serving_fat) {
		$this->serving_fat = $serving_fat;
	}

	public static function findAllByGrMl($value) {
		return static::simpleQuery()->where('gr_ml',$value)->get();
	}

	public static function findByGrMl($value) {
		return static::findBy('gr_ml',$value);
	}

	public function getGrMl() {
		return $this->gr_ml;
	}

	public function setGrMl($gr_ml) {
		$this->gr_ml = $gr_ml;
	}

	public static function findAllByGrCalory($value) {
		return static::simpleQuery()->where('gr_calory',$value)->get();
	}

	public static function findByGrCalory($value) {
		return static::findBy('gr_calory',$value);
	}

	public function getGrCalory() {
		return $this->gr_calory;
	}

	public function setGrCalory($gr_calory) {
		$this->gr_calory = $gr_calory;
	}

	public static function findAllByGrCarb($value) {
		return static::simpleQuery()->where('gr_carb',$value)->get();
	}

	public static function findByGrCarb($value) {
		return static::findBy('gr_carb',$value);
	}

	public function getGrCarb() {
		return $this->gr_carb;
	}

	public function setGrCarb($gr_carb) {
		$this->gr_carb = $gr_carb;
	}

	public static function findAllByGrProtein($value) {
		return static::simpleQuery()->where('gr_protein',$value)->get();
	}

	public static function findByGrProtein($value) {
		return static::findBy('gr_protein',$value);
	}

	public function getGrProtein() {
		return $this->gr_protein;
	}

	public function setGrProtein($gr_protein) {
		$this->gr_protein = $gr_protein;
	}

	public static function findAllByGrFat($value) {
		return static::simpleQuery()->where('gr_fat',$value)->get();
	}

	public static function findByGrFat($value) {
		return static::findBy('gr_fat',$value);
	}

	public function getGrFat() {
		return $this->gr_fat;
	}

	public function setGrFat($gr_fat) {
		$this->gr_fat = $gr_fat;
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