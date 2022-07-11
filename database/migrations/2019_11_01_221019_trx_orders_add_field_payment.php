<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrxOrdersAddFieldPayment extends Migration
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
            $table->string('no_rek')->nullable();
            $table->string('nama_rek')->nullable();
            $table->string('photo_payment')->nullable();
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
