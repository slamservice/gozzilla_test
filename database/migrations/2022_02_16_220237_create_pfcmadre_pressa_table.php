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
        Schema::create('pfcmadre_pressa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pressa_id');
            $table
            ->foreign('pressa_id')
            ->references('id')
            ->on('presse')
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
            $table->unsignedBigInteger('pfcmadre_id');
            $table
            ->foreign('pfcmadre_id')
            ->references('id')
            ->on('pfcmadre')
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
            $table->boolean('serve_robot')-> default(0);
            $table->boolean('stampaggio_automatico')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pfcmadre_pressa');
    }
};
