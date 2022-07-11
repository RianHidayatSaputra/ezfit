<?php namespace App\Http\Controllers;

use App\Models\LogBackend;
use App\Models\TrxOrders;
use Illuminate\Support\Facades\DB;
use crocodicstudio\crudbooster\controllers\CBController;

class AdminLogNoticeController extends CBController {


    public function cbInit()
    {
        $this->setTable("log_backend");
        $this->setPermalink("log_notice");
        $this->setPageTitle("Log Notice");

        $this->addSelectTable("Customer","customers_id",["table"=>"customers","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
        $this->addWysiwyg("Content","content")->strLimit(150);
        $this->addText("Is Read","is_read")->showIndex(false)->strLimit(150)->maxLength(255);
        $this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
        $this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);

    }

    public function getIndex()
    {
        $data['page_title'] = "Log Notice";
        $data['row'] = LogBackend::simpleQuery()
            ->select('log_backend.*','customers.name as customers_name')
            ->join('customers','customers.id','=','log_backend.customers_id')
            ->orderBy('log_backend.id','desc')
        ->get();

        return view('backend.log_notice.index',$data);
    }
    public function getDetail($id)
    {
        $log = LogBackend::findById($id);
        $up['is_read'] =1;

        DB::table('log_backend')->where('id',$log->getId())->update($up);
        if ($log->getTrxOrdersId()->getId() != NULL){
            return redirect('admin/trx_orders/detail/'.$log->getTrxOrdersId()->getId());
        }else{
            return redirect('admin/request_mahasiswa');
        }
    }

    public function postRead(){
        $arr = g('id');

        foreach ($arr as $row) {
            $log = LogBackend::findById($row);
            $up['is_read'] = 1;

            DB::table('log_backend')->where('id',$log->getId())->update($up);
        }

        $result = [];
        return response()->json($result);
    }

    public function getReadAll(){
        $list = LogBackend::findByIsRead(NULL);

        foreach ($list as $key => $row) {
            $log = LogBackend::findById($row->getId());
            $up['is_read'] = 1;

            DB::table('log_backend')->where('id',$log->getId())->update($up);
        }

        return redirect()->back()->with(["message_type"=>'success','message'=>'Mark All Notice as Read']);
    }
}
