<?php
namespace App\Repositories;

use App\Models\Customers;
use DB;
use App\Models\Packages;

class PackagesRepository extends Packages
{
    // TODO : Make you own query methods

    public static function findPackage($id,$package,$t_package = false){
        $cust = Customers::findById($id);
        $site = asset('');
        $type = $cust->getTypeCustomer();
        $list = DB::table('packages');
        if ($t_package){
            $list =$list->where('type_package',$t_package);
        }
        $list=$list->select('packages.*',DB::raw("concat('$site',packages.photo) as photo"))
        ->get();
        if ($type == 'umum'){
            foreach ($list as $row){
                $alergen = json_decode($row->category);
                if ($package == 1){
                    $arr[] = array(
                        'id' => $row->id,
                        'product' => $row->name,
                        'item'=>count($alergen),
                        'photo'=>$row->photo,
                        'price' => number_format($row->price_u1),
                        'real_price'=> $row->price_u1,
                        'fake_price'=>$row->price_uh1,
                    );
                }elseif ($package == 6){
                    $arr[] = array(
                        'id' => $row->id,
                        'product' => $row->name,
                        'item'=>count($alergen),
                        'photo'=>$row->photo,
                        'price' => number_format($row->price_u2),
                        'real_price'=> $row->price_u2,
                        'fake_price'=>$row->price_uh2,
                    );
                }else{
                    $arr[] = array(
                        'id' => $row->id,
                        'product' => $row->name,
                        'item'=>count($alergen),
                        'photo'=>$row->photo,
                        'price' => number_format($row->price_u3),
                        'real_price'=> $row->price_u3,
                        'fake_price'=>$row->price_uh3,
                    );
                }
            }
        }else{
            foreach ($list as $row){
                $alergen = json_decode($row->category);
                if ($package == 1){
                    $arr[] = array(
                        'id' => $row->id,
                        'product' => $row->name,
                        'item'=>count($alergen),
                        'photo'=>$row->photo,
                        'price' => number_format($row->price_m1),
                        'real_price'=> $row->price_m1,
                        'fake_price'=>$row->price_mh1,
                    );
                }elseif ($package == 6){
                    $arr[] = array(
                        'id' => $row->id,
                        'product' => $row->name,
                        'item'=>count($alergen),
                        'photo'=>$row->photo,
                        'price' => number_format($row->price_m2),
                        'real_price'=> $row->price_m2,
                        'fake_price'=>$row->price_mh2,
                    );
                }else{
                    $arr[] = array(
                        'id' => $row->id,
                        'product' => $row->name,
                        'item'=>count($alergen),
                        'photo'=>$row->photo,
                        'price' => number_format($row->price_m3),
                        'real_price'=> $row->price_m3,
                        'fake_price'=>$row->price_mh3,
                    );
                }
            }
        }

        return $arr;
    }

    public static function getPrice($packages_id,$periode,$type){
        $row = Packages::simpleQuery()
        ->where('id',$packages_id)
        ->first();

        if ($type == 'umum'){
            if ($periode == 1){
                $arr = array(
                    'real_price'=> $row->price_u1,
                    'fake_price'=> $row->price_uh1,
                );
            }elseif ($periode == 6){
                $arr = array(
                    'real_price'=> $row->price_u2,
                    'fake_price'=> $row->price_uh2,
                );
            }else{
                $arr = array(
                    'real_price'=> $row->price_u3,
                    'fake_price'=> $row->price_uh3,
                );
            }
        }else{
            if ($periode == 1){
                $arr = array(
                    'real_price'=> $row->price_m1,
                    'fake_price'=> $row->price_mh1,
                );
            }elseif ($periode == 6){
                $arr = array(
                    'real_price'=> $row->price_m2,
                    'fake_price'=> $row->price_mh2,
                );
            }else{
                $arr = array(
                    'real_price'=> $row->price_m3,
                    'fake_price'=> $row->price_mh3,
                );
            }
        }

        return $arr;
    }

    public static function findPack($type_package,$name){
        $find = Packages::simpleQuery()
            ->where('name',$name)
            ->where('type_package',$type_package);
        $find = $find->first();


        return new static($find);
    }

}