<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Menus extends Model
{
    public static $tableName = "menus";

    public static $connection = "mysql";

    
	private $id;
	private $photo;
	private $name;
	private $menu_date;
	private $alergy;
	private $protein;
	private $protein_p;
	private $carbo;
	private $carbo_p;
	private $calory;
	private $calory_p;
	private $fat;
	private $fat_p;
	private $gula;
	private $gula_p;
	private $saturated_fat;
	private $saturated_fat_p;
	private $protein_from;
	private $carbo_from;
	private $product_id;
	private $price_hpp;
	private $price_hpp_p;
	private $created_at;
	private $updated_at;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
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

	public static function findAllByMenuDate($value) {
		return static::simpleQuery()->where('menu_date',$value)->get();
	}

	public static function findByMenuDate($value) {
		return static::findBy('menu_date',$value);
	}

	public function getMenuDate() {
		return $this->menu_date;
	}

	public function setMenuDate($menu_date) {
		$this->menu_date = $menu_date;
	}

	public static function findAllByAlergy($value) {
		return static::simpleQuery()->where('alergy',$value)->get();
	}

	public static function findByAlergy($value) {
		return static::findBy('alergy',$value);
	}

	public function getAlergy() {
		return $this->alergy;
	}

	public function setAlergy($alergy) {
		$this->alergy = $alergy;
	}

	public static function findAllByProtein($value) {
		return static::simpleQuery()->where('protein',$value)->get();
	}

	public static function findByProtein($value) {
		return static::findBy('protein',$value);
	}

	public function getProtein() {
		return $this->protein;
	}

	public function setProtein($protein) {
		$this->protein = $protein;
	}

	public static function findAllByProteinP($value) {
		return static::simpleQuery()->where('protein_p',$value)->get();
	}

	public static function findByProteinP($value) {
		return static::findBy('protein_p',$value);
	}

	public function getProteinP() {
		return $this->protein_p;
	}

	public function setProteinP($protein_p) {
		$this->protein_p = $protein_p;
	}

	public static function findAllByCarbo($value) {
		return static::simpleQuery()->where('carbo',$value)->get();
	}

	public static function findByCarbo($value) {
		return static::findBy('carbo',$value);
	}

	public function getCarbo() {
		return $this->carbo;
	}

	public function setCarbo($carbo) {
		$this->carbo = $carbo;
	}

	public static function findAllByCarboP($value) {
		return static::simpleQuery()->where('carbo_p',$value)->get();
	}

	public static function findByCarboP($value) {
		return static::findBy('carbo_p',$value);
	}

	public function getCarboP() {
		return $this->carbo_p;
	}

	public function setCarboP($carbo_p) {
		$this->carbo_p = $carbo_p;
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

	public static function findAllByCaloryP($value) {
		return static::simpleQuery()->where('calory_p',$value)->get();
	}

	public static function findByCaloryP($value) {
		return static::findBy('calory_p',$value);
	}

	public function getCaloryP() {
		return $this->calory_p;
	}

	public function setCaloryP($calory_p) {
		$this->calory_p = $calory_p;
	}

	public static function findAllByFat($value) {
		return static::simpleQuery()->where('fat',$value)->get();
	}

	public static function findByFat($value) {
		return static::findBy('fat',$value);
	}

	public function getFat() {
		return $this->fat;
	}

	public function setFat($fat) {
		$this->fat = $fat;
	}

	public static function findAllByFatP($value) {
		return static::simpleQuery()->where('fat_p',$value)->get();
	}

	public static function findByFatP($value) {
		return static::findBy('fat_p',$value);
	}

	public function getFatP() {
		return $this->fat_p;
	}

	public function setFatP($fat_p) {
		$this->fat_p = $fat_p;
	}

	public static function findAllByGula($value) {
		return static::simpleQuery()->where('gula',$value)->get();
	}

	public static function findByGula($value) {
		return static::findBy('gula',$value);
	}

	public function getGula() {
		return $this->gula;
	}

	public function setGula($gula) {
		$this->gula = $gula;
	}

	public static function findAllByGulaP($value) {
		return static::simpleQuery()->where('gula_p',$value)->get();
	}

	public static function findByGulaP($value) {
		return static::findBy('gula_p',$value);
	}

	public function getGulaP() {
		return $this->gula_p;
	}

	public function setGulaP($gula_p) {
		$this->gula_p = $gula_p;
	}

	public static function findAllBySaturatedFat($value) {
		return static::simpleQuery()->where('saturated_fat',$value)->get();
	}

	public static function findBySaturatedFat($value) {
		return static::findBy('saturated_fat',$value);
	}

	public function getSaturatedFat() {
		return $this->saturated_fat;
	}

	public function setSaturatedFat($saturated_fat) {
		$this->saturated_fat = $saturated_fat;
	}

	public static function findAllBySaturatedFatP($value) {
		return static::simpleQuery()->where('saturated_fat_p',$value)->get();
	}

	public static function findBySaturatedFatP($value) {
		return static::findBy('saturated_fat_p',$value);
	}

	public function getSaturatedFatP() {
		return $this->saturated_fat_p;
	}

	public function setSaturatedFatP($saturated_fat_p) {
		$this->saturated_fat_p = $saturated_fat_p;
	}

	public static function findAllByProteinFrom($value) {
		return static::simpleQuery()->where('protein_from',$value)->get();
	}

	public static function findByProteinFrom($value) {
		return static::findBy('protein_from',$value);
	}

	public function getProteinFrom() {
		return $this->protein_from;
	}

	public function setProteinFrom($protein_from) {
		$this->protein_from = $protein_from;
	}

	public static function findAllByCarboFrom($value) {
		return static::simpleQuery()->where('carbo_from',$value)->get();
	}

	public static function findByCarboFrom($value) {
		return static::findBy('carbo_from',$value);
	}

	public function getCarboFrom() {
		return $this->carbo_from;
	}

	public function setCarboFrom($carbo_from) {
		$this->carbo_from = $carbo_from;
	}

	public static function findAllByProductId($value) {
		return static::simpleQuery()->where('product_id',$value)->get();
	}

	public static function findByProductId($value) {
		return static::findBy('product_id',$value);
	}

	public function getProductId() {
		return $this->product_id;
	}

	public function setProductId($product_id) {
		$this->product_id = $product_id;
	}

	public static function findAllByPriceHpp($value) {
		return static::simpleQuery()->where('price_hpp',$value)->get();
	}

	public static function findByPriceHpp($value) {
		return static::findBy('price_hpp',$value);
	}

	public function getPriceHpp() {
		return $this->price_hpp;
	}

	public function setPriceHpp($price_hpp) {
		$this->price_hpp = $price_hpp;
	}

	public static function findAllByPriceHppP($value) {
		return static::simpleQuery()->where('price_hpp_p',$value)->get();
	}

	public static function findByPriceHppP($value) {
		return static::findBy('price_hpp_p',$value);
	}

	public function getPriceHppP() {
		return $this->price_hpp_p;
	}

	public function setPriceHppP($price_hpp_p) {
		$this->price_hpp_p = $price_hpp_p;
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