<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_category_id')->nullable();
            $table->string('name')->nullable();
            $table->string('ukuran_serving')->nullable();
            $table->float('ukuran_satuan')->nullable();
            $table->string('jenis_satuan')->nullable();
            $table->float('calory')->nullable();
            $table->float('carbo')->nullable();
            $table->float('sugar')->nullable();
            $table->float('serat')->nullable();
            $table->float('protein')->nullable();
            $table->float('lemak')->nullable();
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
        Schema::dropIfExists('ms_product');
    }
}
