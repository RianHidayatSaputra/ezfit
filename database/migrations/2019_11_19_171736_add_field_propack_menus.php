<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldPropackMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasTable('menus')) {
                Schema::table('menus', function (Blueprint $table) {
                    $table->string('protein_p')->nullable()->after('protein');
                    $table->string('carbo_p')->nullable()->after('carbo');
                    $table->string('calory_p')->nullable()->after('calory');
                    $table->string('fat_p')->nullable()->after('fat');
                    $table->string('gula_p')->nullable()->after('gula');
                    $table->string('saturated_fat_p')->nullable()->after('saturated_fat');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
