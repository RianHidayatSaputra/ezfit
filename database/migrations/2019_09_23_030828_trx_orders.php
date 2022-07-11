<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrxOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customers_id')->nullable();
            $table->string('periode')->nullable();
            $table->integer('packages_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->string('protein')->nullable();
            $table->string('carbo')->nullable();
            $table->text('day_off')->nullable();
            $table->integer('address_book_id')->nullable();
            $table->integer('drivers_id')->nullable();
            $table->string('status_berlangganan')->nullable();
            $table->string('status_payment')->nullable();
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_orders');
    }
}
