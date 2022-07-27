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
        Schema::table('pfc_imballo', function (Blueprint $table) {
            $table->unsignedBigInteger('articolo_id')->nullable()->after('articolo_imballo_id');
            $table
            ->foreign('articolo_id')
            ->references('id')
            ->on('articoli')
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pfc_imballo', function (Blueprint $table) {
            $table->dropColumn('articolo_id');
        });
    }
};
