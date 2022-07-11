<?php namespace App\Http\Controllers;

use DB;
use App\Models\Customers;
use crocodicstudio\crudbooster\controllers\CBController;
use Illuminate\Support\Str;

class AdminNoticeController extends CBController {


    public function cbInit()
    {
        $this->setTable("notice");
        $this->setPermalink("notice");
        $this->setPageTitle("Notice");

        $this->addText("Title","title")->strLimit(150)->maxLength(255);
		$this->addWysiwyg("Description","description")->strLimit(150);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);

    }
    public function postAddSave()
    {
        $arr = g('customers_id');
        foreach ($arr as $l){
            $simpan[] = array(
                'notice_receiver' => $l,
            );
        }
        DB::table('notice_receiver')->insert($simpan);

        $regid = Customers::simpleQuery()
            ->wherein('id',$arr)
            ->pluck('regid')
            ->toArray();

        $regidios = Customers::simpleQuery()
            ->wherein('id',$arr)
            ->pluck('regid_ios')
            ->toArray();

        $mess['content'] = g('description');
        $mess['type_notice'] = 'ongoing';
        $data['title'] = g('title');
        $data['content'] = Str::limit(strip_tags(g('description')),50);
        $data['data'] = $mess;

        $logs[] = SendFcm($regid,$data,'IOS');
        $logs[] = SendFcm($regidios,$data,'IOS');

        $insert['title'] = g('title');
        $insert['description'] = g('description');

        DB::table('notice')->insert($insert);

        foreach ($arr as $row){
            $new_save[] = array(
                'customers_id' => $row,
                'content' => g('description'),
                'created_at' => date('Y-m-d H:i:s'),
            );
        }
        DB::table('log_notice')->insert($new_save);
    }
}
