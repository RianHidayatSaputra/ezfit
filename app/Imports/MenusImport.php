<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Menus;
use Carbon\Carbon;

class MenusImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
    	foreach ($rows as $key => $row) {
            if ($row[0] != '') {
                $menu = New Menus();
                $menu->setPhoto('storage/files/2019/12/08/c12297a6af7e5c57fcdddd43189d51a9.jpg');
                $menu->setName(ucwords($row[0]));
                $menu->setMenuDate(Carbon::parse($row[1])->format('Y-m-d'));
                $menu->setProductId($row[2]);

                $alergy = explode(',', $row[3]);
                $alrg = [];
                if (count($alergy) != 0) {
                    foreach ($alergy as $key => $val) {
                        $alrg[] = array('alergy' => $val);
                    }
                }

                $menu->setAlergy(json_encode($alrg));
                $menu->setProteinFrom($row[4]);
                $menu->setCarboFrom($row[5]);

                $menu->setProtein(round($row[6],2));
                $menu->setCarbo(round($row[7],2));

                $calory = ($row[6] * 4) + ($row[7] * 4) + ($row[8] * 9);
                $menu->setCalory(round($calory, 2));

                $menu->setFat(round($row[8],2));
                $menu->setGula(round($row[9],2));
                $menu->setSaturatedFat(round($row[10],2));

                $menu->setProteinP(round($row[11],2));
                $menu->setCarboP(round($row[12],2));

                $calory_p = ($row[11] * 4) + ($row[12] * 4) + ($row[13] * 9);
                $menu->setCaloryP(round($calory_p,2));

                $menu->setFatP(round($row[13],2));
                $menu->setGulaP(round($row[14],2));
                $menu->setSaturatedFatP(round($row[15],2));

                $menu->setPriceHpp($row[16]);
                $menu->setPriceHppP($row[17]);

                $menu->save();
            }
        }

    }

    public function startRow(): int
    {
    	return 2;
    }
}
