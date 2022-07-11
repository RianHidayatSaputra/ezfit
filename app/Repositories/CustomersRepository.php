<?php
namespace App\Repositories;

use App\Models\AddressBook;
use DB;
use App\Models\Customers;

class CustomersRepository extends Customers
{
    // TODO : Make you own query methods
    public static function findAddress($id,$name){
        $data = AddressBook::simpleQuery()
            ->where('customers_id',$id)
            ->where('name',$name)
            ->first();

        return $data;
    }
    public static function findDetail($name){
        $data = static::simpleQuery()
            ->where('ho_hp',$name)
            ->first();

        return new static($data) ;
    }
    public static function findCustomer($id){
        $data = static::simpleQuery()
            ->select(
                'id',
                'photo',
                'name',
                'email',
                'ho_hp',
                'gender',
                'tinggi',
                'berat',
                'tgl_lahir',
                'type_customer',
                'photo_krs',
                'photo_ktm',
                'status'
            )
            ->where('id',$id)
            ->first();
        $data->photo = asset($data->photo);
        $data->photo_krs = asset($data->photo_krs);
        $data->photo_ktm = asset($data->photo_ktm);

        return $data;
    }

    public static function latestCustomers(){
        $data = static::simpleQuery()
        ->whereNull('status')
        ->orderBy('id','desc')
        ->limit(10)
        ->get();

        return $data;
    }

    public static function requestMahasiswa(){
        $data = static::simpleQuery()
        ->where('is_request',1)
        ->get();

        return $data;
    }

}