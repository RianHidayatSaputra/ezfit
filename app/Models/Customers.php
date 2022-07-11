<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Customers extends Model
{
    public static $tableName = "customers";

    public static $connection = "mysql";

    
	private $id;
	private $photo;
	private $name;
	private $email;
	private $password;
	private $ho_hp;
	private $gender;
	private $tinggi;
	private $berat;
	private $tgl_lahir;
	private $type_customer;
	private $photo_krs;
	private $photo_ktm;
	private $status;
	private $start_date;
	private $end_date;
	private $created_at;
	private $updated_at;
	private $is_request;
	private $regid;
	private $regid_ios;

    /**
     * @return mixed
     */
    public function getRegidIos()
    {
        return $this->regid_ios;
    }

    /**
     * @param mixed $regid_ios
     */
    public function setRegidIos($regid_ios): void
    {
        $this->regid_ios = $regid_ios;
    }

    /**
     * @return mixed
     */
    public function getRegid()
    {
        return $this->regid;
    }

    /**
     * @param mixed $regid
     */
    public function setRegid($regid): void
    {
        $this->regid = $regid;
    }

    /**
     * @return mixed
     */
    public function getisRequest()
    {
        return $this->is_request;
    }

    /**
     * @param mixed $is_request
     */
    public function setIsRequest($is_request): void
    {
        $this->is_request = $is_request;
    }

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

	public static function findAllByEmail($value) {
		return static::simpleQuery()->where('email',$value)->get();
	}

	public static function findByEmail($value) {
		return static::findBy('email',$value);
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public static function findAllByPassword($value) {
		return static::simpleQuery()->where('password',$value)->get();
	}

	public static function findByPassword($value) {
		return static::findBy('password',$value);
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public static function findAllByHoHp($value) {
		return static::simpleQuery()->where('ho_hp',$value)->get();
	}

	public static function findByHoHp($value) {
		return static::findBy('ho_hp',$value);
	}

	public function getHoHp() {
		return $this->ho_hp;
	}

	public function setHoHp($ho_hp) {
		$this->ho_hp = $ho_hp;
	}

	public static function findAllByGender($value) {
		return static::simpleQuery()->where('gender',$value)->get();
	}

	public static function findByGender($value) {
		return static::findBy('gender',$value);
	}

	public function getGender() {
		return $this->gender;
	}

	public function setGender($gender) {
		$this->gender = $gender;
	}

	public static function findAllByTinggi($value) {
		return static::simpleQuery()->where('tinggi',$value)->get();
	}

	public static function findByTinggi($value) {
		return static::findBy('tinggi',$value);
	}

	public function getTinggi() {
		return $this->tinggi;
	}

	public function setTinggi($tinggi) {
		$this->tinggi = $tinggi;
	}

	public static function findAllByBerat($value) {
		return static::simpleQuery()->where('berat',$value)->get();
	}

	public static function findByBerat($value) {
		return static::findBy('berat',$value);
	}

	public function getBerat() {
		return $this->berat;
	}

	public function setBerat($berat) {
		$this->berat = $berat;
	}

	public static function findAllByTglLahir($value) {
		return static::simpleQuery()->where('tgl_lahir',$value)->get();
	}

	public static function findByTglLahir($value) {
		return static::findBy('tgl_lahir',$value);
	}

	public function getTglLahir() {
		return $this->tgl_lahir;
	}

	public function setTglLahir($tgl_lahir) {
		$this->tgl_lahir = $tgl_lahir;
	}

	public static function findAllByTypeCustomer($value) {
		return static::simpleQuery()->where('type_customer',$value)->get();
	}

	public static function findByTypeCustomer($value) {
		return static::findBy('type_customer',$value);
	}

	public function getTypeCustomer() {
		return $this->type_customer;
	}

	public function setTypeCustomer($type_customer) {
		$this->type_customer = $type_customer;
	}

	public static function findAllByPhotoKrs($value) {
		return static::simpleQuery()->where('photo_krs',$value)->get();
	}

	public static function findByPhotoKrs($value) {
		return static::findBy('photo_krs',$value);
	}

	public function getPhotoKrs() {
		return $this->photo_krs;
	}

	public function setPhotoKrs($photo_krs) {
		$this->photo_krs = $photo_krs;
	}

	public static function findAllByPhotoKtm($value) {
		return static::simpleQuery()->where('photo_ktm',$value)->get();
	}

	public static function findByPhotoKtm($value) {
		return static::findBy('photo_ktm',$value);
	}

	public function getPhotoKtm() {
		return $this->photo_ktm;
	}

	public function setPhotoKtm($photo_ktm) {
		$this->photo_ktm = $photo_ktm;
	}

	public static function findAllByStatus($value) {
		return static::simpleQuery()->where('status',$value)->get();
	}

	public static function findByStatus($value) {
		return static::findBy('status',$value);
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public static function findAllByStartDate($value) {
		return static::simpleQuery()->where('start_date',$value)->get();
	}

	public static function findByStartDate($value) {
		return static::findBy('start_date',$value);
	}

	public function getStartDate() {
		return $this->start_date;
	}

	public function setStartDate($start_date) {
		$this->start_date = $start_date;
	}

	public static function findAllByEndDate($value) {
		return static::simpleQuery()->where('end_date',$value)->get();
	}

	public static function findByEndDate($value) {
		return static::findBy('end_date',$value);
	}

	public function getEndDate() {
		return $this->end_date;
	}

	public function setEndDate($end_date) {
		$this->end_date = $end_date;
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