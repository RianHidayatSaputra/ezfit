<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class TrxOrdersStatus extends Model
{
    public static $tableName = "trx_orders_status";

    public static $connection = "mysql";


    private $id;
    private $trx_orders_id;
    private $status_pengiriman;
    private $date;
    private $created_at;
    private $updated_at;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }



    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public static function findAllByTrxOrdersId($value) {
        return static::simpleQuery()->where('trx_orders_id',$value)->get();
    }

    /**
     * @return TrxOrders
     */
    public function getTrxOrdersId() {
        return TrxOrders::findById($this->trx_orders_id);
    }

    public function setTrxOrdersId($trx_orders_id) {
        $this->trx_orders_id = $trx_orders_id;
    }

    public static function findAllByStatusPengiriman($value) {
        return static::simpleQuery()->where('status_pengiriman',$value)->get();
    }

    public static function findByStatusPengiriman($value) {
        return static::findBy('status_pengiriman',$value);
    }

    public function getStatusPengiriman() {
        return $this->status_pengiriman;
    }

    public function setStatusPengiriman($status_pengiriman) {
        $this->status_pengiriman = $status_pengiriman;
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