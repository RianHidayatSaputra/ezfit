<?php namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\LogNotice;
use crocodicstudio\crudbooster\controllers\CBController;
use crocodicstudio\crudbooster\controllers\partials\ButtonColor;
use Carbon\Carbon;

class AdminRequestMahasiswaController extends CBController {

    public function cbInit()
    {
        $this->setTable("customers");
        $this->setPermalink("request_mahasiswa");
        $this->setPageTitle("Request Mahasiswa");
        $this->setButtonEdit(false);
        $this->setButtonAdd(false);
        $this->setButtonAddMore(false);

        $this->addImage("Photo","photo")->encrypt(true);
        $this->addText("Name","name")->strLimit(150)->maxLength(255);
        $this->addText("Is Request","is_request")->showIndex(false)->showDetail(false);
        $this->addEmail("Email","email");
        $this->addText("Ho Hp","ho_hp")->strLimit(150)->maxLength(255);
        $this->addText("Gender","gender")->strLimit(150)->maxLength(255);
        $this->addText("Tgl Lahir","tgl_lahir")->strLimit(150)->maxLength(255);
        $this->addImage("Photo KRS","photo_krs")->encrypt(true);
        $this->addImage("Photo KTM","photo_ktm")->encrypt(true);
        $this->addText("Approve","id")->indexDisplayTransform(function ($row) {
            return '<button data-toggle="modal" data-target="#approveMahasiswa" class="btn btn-xs btn-primary btn-sm"><i class="fa fa-upload"></i>&nbsp; Approve</button>
            <a class="btn btn-xs btn-danger btn-sm" href="'.action('AdminRequestMahasiswaController@getChangeReject').'/'.$row.'"><i class="fa fa-download"></i>&nbsp; Reject</a>
            <div class="modal fade in" tabindex="-1" role="dialog" id="approveMahasiswa">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal"><span aria-hidden="true">x</span></button>
            <h4 class="modal-title"><i class="fa fa-download"></i> &nbsp; Approve Mahasiswa</h4>
            </div>
            <form action="'.action('AdminRequestMahasiswaController@getChangeSuccess').'" method="GET" enctype="multipart/form-data">
            <input type="text" value="'.$row.'" hidden name="id">
            <div class="modal-body">
            <div class="form-group">
            <label style="font-size: 14px;">Date Start</label>
            <input type="date" class="form-control" name="date_start" value="'.date('Y-m-d').'">
            </div>
            <div class="form-group">
            <label style="font-size: 14px;">Date End</label>
            <input type="date" class="form-control" name="date_end" value="'.date('Y-m-d').'">
            </div>
            </div>
            <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Submit</button>
            </div>
            </form>
            </div>
            </div>
            </div>';
        });

        $this->hookIndexQuery(function($query) {
            // Todo: code query here

            // You can make query like laravel db builder
            $query->where("is_request", 1);

            // Don't forget to return back
            return $query;
        });
    }

    public function getChangeSuccess(){
        $check = Customers::findById(g('id'));
        $check->setIsRequest(NULL);
        $check->setStartDate(g('date_start'));
        $check->setEndDate(g('date_end'));
        $check->setTypeCustomer('mahasiswa');
        $check->save();

        $regids[] = $check->getRegid();
        $regid_ioss[] = $check->getRegidIos();

        $data['title'] = 'Approval Mahasiswa ';
        $conten = 'Selamat! Kamu sudah menjadi member mahasiswa. Silakan menikmati potongan harga spesial.';
        $mess['content'] = $conten;
        $mess['type']   = 'account';
        $data['content'] = $mess;
        if ($regids){
            $notice[] = SendFcm($regids,$data,'IOS');
        }
        if($regid_ioss){
            $notice[] = SendFcm($regid_ioss,$data,'IOS');
        }

        $log = new LogNotice();
        $log->setCustomersId($check->getId());
        $log->setContent($conten);
        $log->setCreatedAt(date('Y-m-d H:i:s'));
        $log->save();

        return cb()->redirect(action("AdminRequestMahasiswaController@getIndex"),'Success Update',"success");
    }

    public function getChangeReject($id){
        $check = Customers::findById($id);
        $check->setTypeCustomer('umum');
        $check->setPhotoKrs(NULL);
        $check->setPhotoKtm(NULL);
        $check->setIsRequest(NULL);
        $check->save();

        $regids[] = $check->getRegid();
        $regid_ioss[] = $check->getRegidIos();

        $data['title'] = 'Approval Mahasiswa';
        $conten = 'Maaf permintaan perubahan account sebagai mahasiswa anda ditolak';
        $mess['content'] = $conten;
        $mess['type']   = 'account';
        $data['content'] = $conten;
        $data['data'] = $mess;

        if ($regids){
            $notice[] = SendFcm($regids,$data,'IOS');
        }
        if($regid_ioss){
            $notice[] = SendFcm($regid_ioss,$data,'IOS');
        }

        $log = new LogNotice();
        $log->setCustomersId($check->getId());
        $log->setContent($conten);
        $log->setType('account');
        $log->setTitle('Approval Mahasiswa');
        $log->setTypeNotice(NULL);
        $log->setCreatedAt(date('Y-m-d H:i:s'));
        $log->save();

        return cb()->redirect(action("AdminRequestMahasiswaController@getIndex"),'Success Rejected!',"success");
    }
}
