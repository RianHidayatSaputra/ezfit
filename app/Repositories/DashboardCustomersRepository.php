<?php
namespace App\Repositories;

use DB;
use App\Models\DashboardCustomers;

class DashboardCustomersRepository extends DashboardCustomers
{
    // TODO : Make you own query methods
    public static function GetValue($slug,$id){
        $dash = static::simpleQuery()
            ->where('slug',$slug)
            ->where('customers_id',$id)
            ->first();

        return $dash->answer;
    }

}