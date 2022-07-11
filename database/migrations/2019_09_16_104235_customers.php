<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Customers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('photo')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('ho_hp')->nullable();
            $table->string('gender')->nullable();
            $table->integer('tinggi')->nullable();
            $table->integer('berat')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('type_customer')->nullable();
            $table->string('photo_krs')->nullable();
            $table->string('photo_ktm')->nullable();
            $table->string('status')->nullable();
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
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
        Schema::dropIfExists('customers');
    }
}
