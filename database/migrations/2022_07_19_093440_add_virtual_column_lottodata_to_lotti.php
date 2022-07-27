<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lotti', function (Blueprint $table) {
            $table->string('lotto_dataLotto')->virtualAs("concat(lotti.lotto,' del ', DATE_FORMAT(lotti.data_lotto, '%d/%m/%Y'))")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lotti', function (Blueprint $table) {
            $table->dropColumn('lotto_dataLotto');
        });
    }
};
