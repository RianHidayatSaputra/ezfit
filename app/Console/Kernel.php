<?php

namespace App\Console;

use App\Models\LogNotice;
use App\Models\TrxOrdersDate;
use DB;
use App\Models\Customers;
use App\Models\Drivers;
use App\Models\TrxOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Repositories\VouchersRepository;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            if (date('Y-m-d H:i:s') >= date('Y-m-d 20:00:00')){
                $date = getMustEnd(date('Y-m-d'),1);
            }else{
                $date = date('Y-m-d');
            }
            $d = HariApa($date);
            if ($d == 'minggu'){
                $date = getMustEnd($date,1);
            }else{
                $date = $date;
            }
            $check = DB::table('this_day_log')->where('date',$date)->count();
            if($check == 0 ){
                $order = TrxOrders::simpleQuery()
                    ->leftjoin(
                        DB::raw("(SELECT trx_orders_status.trx_orders_id,count(trx_orders_status.id) as total_pengiriman 
                        FROM trx_orders_status GROUP BY trx_orders_status.trx_orders_id) as total_pengiriman"),
                        "total_pengiriman.trx_orders_id","=","trx_orders.id")
                    ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                    ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                    ->join('customers','customers.id','=','trx_orders.customers_id')
                    ->select('trx_orders.*',
                        'customers.regid as regid',
                        'customers.regid_ios as regid_ios',
                        'customers.name as cs_name',
                        'total_pengiriman'
                    )
                    ->whereNull('trx_orders.is_paused')
                    ->get();

                $regids = [];
                $regid_ioss = [];
                foreach ($order as $l){
                    if(($l->periode-$l->total_pengiriman) == 3 OR ($l->periode-$l->total_pengiriman) == 2 OR ($l->periode-$l->total_pengiriman) == 1){
                        if ($l->regid){
                            $regids[] = $l->regid;
                        }
                        if ($l->regid_ios){
                            $regid_ioss[] = $l->regid_ios;
                        }
                    }
                }
                $data_expired['title'] = 'Pemberitahuan Order';
                $cont = 'Status pembelian anda akan segera selesai';
                $messde['content'] = $cont;
                $messde['type']   = 'order_expired';
                $messde['type_notice'] = 'ongoing';
                $data_expired['content'] = $cont;
                $data_expired['data'] = $messde;
                if ($regids){
                    SendFcm($regids,$data_expired,'IOS');
                }
                if ($regid_ioss){
                    SendFcm($regid_ioss,$data_expired,'IOS');
                }
                $inse['date'] = $date;
                DB::table('this_day_log')->insert($inse);
            }

            // mahasiswa expired
            $mhs = Customers::simpleQuery()
                ->where('type_customer','mahasiswa')
                ->whereNotNull('start_date')
                ->where('end_date','>=',date('Y-m-d'))
                ->get();
            $regid = [];
            $id_reg = [];
            $regidIos = [];
            foreach ($mhs as $y){
                if ($y->end_date <= date('Y-m-d')){
                    $regid[] = $y->regid;
                    $regidIos[] = $y->regid_ios;
                    $id_reg[] = $y->id;
                }
            }
            if($id_reg){
                $update_status['type_customer'] = 'umum';
                $update_status['photo_krs'] = NULL;
                $update_status['photo_ktm'] = NULL;

                DB::table('customers')->wherein('id',$id_reg)->update($update_status);
            }

            $data_account['title'] = 'Account Mahasiswa Expired';
            $conten_e = 'Status mahasiswa pada akun anda sudah kadaluarsa silahkan perbarui';
            $messme['content'] = $conten_e;
            $messme['type']   = 'account';
            $data_account['content'] = $conten_e;
            $data_account['content'] = $messme;
            if ($regid){
                SendFcm($regid,$data_account,'IOS');
            }
            if($regidIos){
                SendFcm($regidIos,$data_account,'IOS');
            }

            $list = TrxOrdersDate::simpleQuery()
                ->join('trx_orders','trx_orders.id','trx_orders_date.trx_orders_id')
                ->leftjoin('drivers','drivers.id','=','trx_orders.drivers_id')
                ->leftjoin('packages','packages.id','=','trx_orders.packages_id')
                ->leftjoin('customers','customers.id','=','trx_orders.customers_id')
                ->where('trx_orders.status_payment','!=','Failed')
                ->where('date',$date)
                ->whereNull('trx_orders.is_paused')
                ->select(
                    'trx_orders.*',
                    'drivers.name as driver_name',
                    'packages.name as package_name',
                    'customers.name as c_name',
                    'customers.ho_hp as c_ho_hp',
                    'customers.regid as regid',
                    'customers.regid_ios as regid_ios'
                )
                ->get();

            $regid_a = [];
            $regid_i = [];
            foreach ($list as $row){
                if ($row->status_payment == 'Success Payment'){
                    if (!$row->nama_penerima){
                        $row->nama_penerima = $row->c_name;
                    }
                    if (!$row->no_penerima){
                        $row->no_penerima = $row->c_ho_hp;
                    }
                    $check = DB::table('trx_orders_status')
                        ->where('trx_orders_id',$row->id)
                        ->where('status_pengiriman','Proses')
                        ->whereDate('date', $date)
                        ->count();

                    if ($check == 0){
                        $in['trx_orders_id'] = $row->id;
                        $in['status_pengiriman'] = 'Proses';
                        $in['date'] = $date;
                        $in['created_at'] = date('Y-m-d H:i:s');

                        DB::table('trx_orders_status')->insert($in);

                        $content = 'Katering kamu hari ini sedang diproses, harap bersabar menunggu ya.';
                        $mess['type'] = 'order';
                        $mess['id_order'] = $row->id;
                        $mess['content'] = $content;
                        $mess['type_notice'] = 'ongoing';
                        $data['title'] = 'Status Pembayaran';
                        $data['content'] = $content;
                        $data['data'] = $mess;

                        $regid_a[] = $row->regid;
                        $regid_i[] = $row->regid_ios;

                        if ($regid_a){
                            $logs[] = SendFcm($regid_a,$data,'IOS');
                        }
                        if($regid_i){
                            $logs[] = SendFcm($regid_i,$data,'IOS');
                        }

                        $log = new LogNotice();
                        $log->setCustomersId($row->customers_id);
                        $log->setTrxOrdersId($row->id);
                        $log->setTitle('Status Pengiriman');
                        $log->setTypeNotice('ongoing');
                        $log->setContent($content);
                        $log->setCreatedAt(date('Y-m-d H:i:s'));
                        $log->save();
                    }
                }elseif($row->status_payment == 'Waiting Payment'){
                    $check = LogNotice::simpleQuery()
                        ->where('customers_id',$row->customers_id)
                        ->where('trx_orders_id',$row->id)
                        ->where('title','Orders Awaiting Payment')
                        ->count();

                    if($check == 0){
                        $content = 'Pembayaran katering anda tertunda, apakah anda ingin melanjutkan pembayaran ?.';
                        $mess['type'] = 'order';
                        $mess['id_order'] = $row->id;
                        $mess['content'] = $content;
                        $mess['type_notice'] = 'ongoing';
                        $data['title'] = 'Orders Awaiting Payment';
                        $data['content'] = $content;
                        $data['data'] = $mess;

                        $regid_a[] = $row->regid;
                        $regid_i[] = $row->regid_ios;

                        if ($regid_a){
                            $logs[] = SendFcm($regid_a,$data,'IOS');
                        }
                        if($regid_i){
                            $logs[] = SendFcm($regid_i,$data,'IOS');
                        }

                        $log = new LogNotice();
                        $log->setCustomersId($row->customers_id);
                        $log->setTrxOrdersId($row->id);
                        $log->setTitle('Orders Awaiting Payment');
                        $log->setTypeNotice('ongoing');
                        $log->setContent($content);
                        $log->setCreatedAt(date('Y-m-d H:i:s'));
                        $log->save();
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
