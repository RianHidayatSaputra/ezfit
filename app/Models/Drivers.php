<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Drivers extends Model
{
    public static $tableName = "drivers";

    public static $connection = "mysql";

    
	private $id;
	private $name;
	private $photo;
	private $kode;
	private $no_wa;
	private $email;
	private $password;
	private $users_id;
	private $created_at;
	private $updated_at;

    /**
     * @return mixed
     */
    public function getUsersId()
    {
        return $this->users_id;
    }

    /**
     * @param mixed $users_id
     */
    public function setUsersId($users_id): void
    {
        $this->users_id = $users_id;
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

	public static function findAllByKode($value) {
		return static::simpleQuery()->where('kode',$value)->get();
	}

	public static function findByKode($value) {
		return static::findBy('kode',$value);
	}

	public function getKode() {
		return $this->kode;
	}

	public function setKode($kode) {
		$this->kode = $kode;
	}

	public static function findAllByNoWa($value) {
		return static::simpleQuery()->where('no_wa',$value)->get();
	}

	public static function findByNoWa($value) {
		return static::findBy('no_wa',$value);
	}

	public function getNoWa() {
		return $this->no_wa;
	}

	public function setNoWa($no_wa) {
		$this->no_wa = $no_wa;
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