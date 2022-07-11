<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Packages extends Model
{
    public static $tableName = "packages";

    public static $connection = "mysql";

    
	private $id;
	private $name;
	private $category;
	private $type_package;
	private $price_u1;
	private $price_u2;
	private $price_u3;
	private $price_m1;
	private $price_m2;
	private $price_m3;
	private $created_at;
	private $updated_at;
    private $price_uh1;
    private $price_uh2;
    private $price_uh3;
    private $price_mh1;
    private $price_mh2;
    private $price_mh3;
    private $item_total;
    private $photo;

    /**
     * @return mixed
     */
    public function getTypePackage()
    {
        return $this->type_package;
    }

    /**
     * @param mixed $type_package
     */
    public function setTypePackage($type_package): void
    {
        $this->type_package = $type_package;
    }

    /**
     * @return mixed
     */

    public function getItemTotal()
    {
        return $this->item_total;
    }

    /**
     * @param mixed $item_total
     */
    public function setItemTotal($item_total): void
    {
        $this->item_total = $item_total;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getPriceUh1()
    {
        return $this->price_uh1;
    }

    /**
     * @param mixed $price_uh1
     */
    public function setPriceUh1($price_uh1): void
    {
        $this->price_uh1 = $price_uh1;
    }

    /**
     * @return mixed
     */
    public function getPriceUh2()
    {
        return $this->price_uh2;
    }

    /**
     * @param mixed $price_uh2
     */
    public function setPriceUh2($price_uh2): void
    {
        $this->price_uh2 = $price_uh2;
    }

    /**
     * @return mixed
     */
    public function getPriceUh3()
    {
        return $this->price_uh3;
    }

    /**
     * @param mixed $price_uh3
     */
    public function setPriceUh3($price_uh3): void
    {
        $this->price_uh3 = $price_uh3;
    }

    /**
     * @return mixed
     */
    public function getPriceMh1()
    {
        return $this->price_mh1;
    }

    /**
     * @param mixed $price_mh1
     */
    public function setPriceMh1($price_mh1): void
    {
        $this->price_mh1 = $price_mh1;
    }

    /**
     * @return mixed
     */
    public function getPriceMh2()
    {
        return $this->price_mh2;
    }

    /**
     * @param mixed $price_mh2
     */
    public function setPriceMh2($price_mh2): void
    {
        $this->price_mh2 = $price_mh2;
    }

    /**
     * @return mixed
     */
    public function getPriceMh3()
    {
        return $this->price_mh3;
    }

    /**
     * @param mixed $price_mh3
     */
    public function setPriceMh3($price_mh3): void
    {
        $this->price_mh3 = $price_mh3;
    }

    
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

	public static function findAllByCategory($value) {
		return static::simpleQuery()->where('category',$value)->get();
	}

	public static function findByCategory($value) {
		return static::findBy('category',$value);
	}

	public function getCategory() {
		return $this->category;
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	public static function findAllByPriceU1($value) {
		return static::simpleQuery()->where('price_u1',$value)->get();
	}

	public static function findByPriceU1($value) {
		return static::findBy('price_u1',$value);
	}

	public function getPriceU1() {
		return $this->price_u1;
	}

	public function setPriceU1($price_u1) {
		$this->price_u1 = $price_u1;
	}

	public static function findAllByPriceU2($value) {
		return static::simpleQuery()->where('price_u2',$value)->get();
	}

	public static function findByPriceU2($value) {
		return static::findBy('price_u2',$value);
	}

	public function getPriceU2() {
		return $this->price_u2;
	}

	public function setPriceU2($price_u2) {
		$this->price_u2 = $price_u2;
	}

	public static function findAllByPriceU3($value) {
		return static::simpleQuery()->where('price_u3',$value)->get();
	}

	public static function findByPriceU3($value) {
		return static::findBy('price_u3',$value);
	}

	public function getPriceU3() {
		return $this->price_u3;
	}

	public function setPriceU3($price_u3) {
		$this->price_u3 = $price_u3;
	}

	public static function findAllByPriceM1($value) {
		return static::simpleQuery()->where('price_m1',$value)->get();
	}

	public static function findByPriceM1($value) {
		return static::findBy('price_m1',$value);
	}

	public function getPriceM1() {
		return $this->price_m1;
	}

	public function setPriceM1($price_m1) {
		$this->price_m1 = $price_m1;
	}

	public static function findAllByPriceM2($value) {
		return static::simpleQuery()->where('price_m2',$value)->get();
	}

	public static function findByPriceM2($value) {
		return static::findBy('price_m2',$value);
	}

	public function getPriceM2() {
		return $this->price_m2;
	}

	public function setPriceM2($price_m2) {
		$this->price_m2 = $price_m2;
	}

	public static function findAllByPriceM3($value) {
		return static::simpleQuery()->where('price_m3',$value)->get();
	}

	public static function findByPriceM3($value) {
		return static::findBy('price_m3',$value);
	}

	public function getPriceM3() {
		return $this->price_m3;
	}

	public function setPriceM3($price_m3) {
		$this->price_m3 = $price_m3;
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