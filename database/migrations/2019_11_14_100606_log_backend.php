<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogBackend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_backend', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customers_id')->nullable();
            $table->integer('trx_orders_id')->nullable();
            $table->text('content')->nullable();
            $table->integer('is_read')->nullable();
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
        Schema::dropIfExists('log_backend');
    }
}
