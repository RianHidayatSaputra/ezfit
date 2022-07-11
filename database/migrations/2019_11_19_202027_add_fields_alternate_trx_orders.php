<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsAlternateTrxOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('trx_orders')) {
            Schema::table('trx_orders', function (Blueprint $table) {
                $table->string('protein_alternative')->nullable()->after('protein');
                $table->string('carbo_alternative')->nullable()->after('carbo');
            });
        }
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
