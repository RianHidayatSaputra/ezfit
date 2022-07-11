<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=abc.xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
?>
<style>
    table{
        width: 100%;
        border-collapse: collapse;
    }
    tr,td,th{
        text-align: center;
        border: 1px solid#000;
    }
</style>
<table>
    <tr>
        <th>No Order</th>
        <th>Periode</th>
        <th>Apakah Libur</th>
        <th>Tanggal Mulai</th>
        <th>Hari Libur</th>
        <th>Tanggal Selesai</th>
        <th>Terakhir Kirim</th>
        <th>Libur Terakhir</th>
        <th>Jumlah terkirim</th>
        <th>Sisa</th>
        <th>Apakah Sesuai</th>
    </tr>
    @foreach($list as $y)
        <?php
        $terkirim = DB::table('trx_orders_status')
            ->where('trx_orders_id',$y->id)
            ->select('date')
            ->groupby('date')
            ->get();
        $jml = 0;
        foreach ($terkirim as $jum){
            $jml += 1;
        }
        $l = json_decode($y->day_off);
        $lib = 'minggu,';
        if (!empty($l)){
            foreach ($l as $li){
                $lib .= $li->day_off.',';
            }
        }
        if ($y->is_paused){
            $libur = 'Libur';
        }else{
            $libur = 'Tidak';
        }
        $terakhir = DB::table('trx_orders_status')
            ->where('trx_orders_id',$y->id)
            ->orderby('date','desc')
            ->first();

        $libur_terakhir = DB::table('trx_orders_pause_date')
            ->where('trx_orders_id',$y->id)
            ->orderby('date','desc')
            ->first();
        ?>
        <tr>
            <td>{{$y->no_order}}</td>
            <td>{{$y->periode}}</td>
            <td>{{$libur}}</td>
            <td>{{$y->tgl_mulai}}</td>
            <td>{{$lib}}</td>
            <td>{{$y->must_end}}</td>
            <td>
                @if($terakhir)
                    {{$terakhir->date}}
                @endif
            </td>
            <td>
                @if($libur_terakhir)
                    {{$libur_terakhir->date}}
                @endif
            </td>
            <td>{{$jml}}</td>
            <td>{{$y->periode -  $jml}}</td>
            <td></td>
        </tr>
    @endforeach
</table>