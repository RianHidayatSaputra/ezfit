<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrxOrdersStatusAddDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_orders_status', function (Blueprint $table) {
            //
            $table->string('photo_pengiriman')->nullable();
            $table->string('catatan_pengiriman')->nullable();
            $table->string('penerima_pengiriman')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_orders_status', function (Blueprint $table) {
            //
        });
    }
}
