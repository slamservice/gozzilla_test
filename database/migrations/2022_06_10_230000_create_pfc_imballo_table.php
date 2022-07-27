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
        Schema::create('pfc_imballo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('articolo_imballo_id');
            $table->integer('nr_conf_per_scatola');
            //$table->integer('qty_imballi');
            $table->integer('sort');

            $table
            ->foreign('articolo_imballo_id')
            ->references('id')
            ->on('articoli')
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
            $table->unsignedBigInteger('pfc_id');
            $table
            ->foreign('pfc_id')
            ->references('id')
            ->on('pfc')
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');

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
        Schema::dropIfExists('pfc_imballo');
    }
};
