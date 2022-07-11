<?php
namespace App\Repositories;

use DB;
use App\Models\AddressBook;

class AddressBookRepository extends AddressBook
{
    // TODO : Make you own query methods
    public static function findAddress($id){
        $list = static::simpleQuery()
        ->where('customers_id',$id)
        ->get();

        return $list;
    }

    public static function checkAddress($id,$name){
        return static::simpleQuery()
        ->where('customers_id',$id)
        ->where('name',$name)
        ->count();
    }

    public static function newAddress($id,$name,$address,$lat,$long,$detail){
    	$action = New AddressBook();
    	$action->setCustomersId($id);
    	$action->setName($name);
    	$action->setAddress($address);
    	$action->setLatitude($lat);
    	$action->setLongitude($long);
    	$action->setReceiver('Belum di isi developer');
    	$action->setDetailAddress($detail);
    	$action->save();

    	if ($action) {
    		return 'Success';
    	}else{
    		return 'Warning';
    	}

    }

}