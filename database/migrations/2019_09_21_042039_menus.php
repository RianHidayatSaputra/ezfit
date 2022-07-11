<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Menus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('photo')->nullable();
            $table->string('name')->nullable();
            $table->string('menu_date')->nullable();
            $table->string('alergy')->nullable();
            $table->string('protein')->nullable();
            $table->string('carbo')->nullable();
            $table->string('calory')->nullable();
            $table->string('fat')->nullable();
            $table->string('gula')->nullable();
            $table->string('saturated_fat')->nullable();
            $table->string('protein_from')->nullable();
            $table->string('carbo_from')->nullable();
            $table->string('product_id')->nullable();
            $table->string('price_hpp')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
