<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class AddressBook extends Model
{
    public static $tableName = "address_book";

    public static $connection = "mysql";

    
	private $id;
	private $customers_id;
	private $name;
	private $address;
	private $latitude;
	private $longitude;
	private $receiver;
	private $drivers_id;
	private $created_at;
	private $updated_at;
	private $no_penerima;
    private $detail_address;
    private $catatan;

    /**
     * @return mixed
     */
    public function getCatatan()
    {
        return $this->catatan;
    }

    /**
     * @param mixed $catatan
     */
    public function setCatatan($catatan): void
    {
        $this->catatan = $catatan;
    }
    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByCustomersId($value) {
		return static::simpleQuery()->where('customers_id',$value)->get();
	}

	/**
	* @return Customers
	*/
	public function getCustomersId() {
		return Customers::findById($this->customers_id);
	}

	public function setCustomersId($customers_id) {
		$this->customers_id = $customers_id;
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

	public static function findAllByAddress($value) {
		return static::simpleQuery()->where('address',$value)->get();
	}

	public static function findByAddress($value) {
		return static::findBy('address',$value);
	}

	public function getAddress() {
		return $this->address;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public static function findAllByLatitude($value) {
		return static::simpleQuery()->where('latitude',$value)->get();
	}

	public static function findByLatitude($value) {
		return static::findBy('latitude',$value);
	}

	public function getLatitude() {
		return $this->latitude;
	}

	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}

	public static function findAllByLongitude($value) {
		return static::simpleQuery()->where('longitude',$value)->get();
	}

	public static function findByLongitude($value) {
		return static::findBy('longitude',$value);
	}

	public function getLongitude() {
		return $this->longitude;
	}

	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}

	public static function findAllByReceiver($value) {
		return static::simpleQuery()->where('receiver',$value)->get();
	}

	public static function findByReceiver($value) {
		return static::findBy('receiver',$value);
	}

	public function getReceiver() {
		return $this->receiver;
	}

	public function setReceiver($receiver) {
		$this->receiver = $receiver;
	}

	public static function findAllByDriversId($value) {
		return static::simpleQuery()->where('drivers_id',$value)->get();
	}

	/**
	* @return Drivers
	*/
	public function getDriversId() {
		return Drivers::findById($this->drivers_id);
	}

	public function setDriversId($drivers_id) {
		$this->drivers_id = $drivers_id;
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

	public static function findAllByNoPenerima($value) {
		return static::simpleQuery()->where('no_penerima',$value)->get();
	}

	public static function findByNoPenerima($value) {
		return static::findBy('no_penerima',$value);
	}

	public function getNoPenerima() {
		return $this->no_penerima;
	}

	public function setNoPenerima($no_penerima) {
		$this->no_penerima = $no_penerima;
	}

	public static function findAllByDetailAddress($value) {
		return static::simpleQuery()->where('detail_address',$value)->get();
	}

	public static function findByDetailAddress($value) {
		return static::findBy('detail_address',$value);
	}

	public function getDetailAddress() {
		return $this->detail_address;
	}

	public function setDetailAddress($detail_address) {
		$this->detail_address = $detail_address;
	}


}