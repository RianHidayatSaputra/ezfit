<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldAddress2TrxOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_orders', function (Blueprint $table) {
            //
            $table->string('address_name_second')->nullable();
            $table->string('address_second')->nullable();
            $table->string('detail_address_second')->nullable();
            $table->string('latitude_second')->nullable();
            $table->string('longitude_second')->nullable();
            $table->string('nama_penerima_second')->nullable();
            $table->string('no_penerima_second')->nullable();
            $table->integer('drivers_id_second')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_orders', function (Blueprint $table) {
            //
        });
    }
}
