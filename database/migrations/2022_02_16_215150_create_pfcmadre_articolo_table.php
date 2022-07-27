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
        Schema::create('pfcmadre_articolo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('articolo_id');
            $table
                ->foreign('articolo_id')
                ->references('id')
                ->on('articoli')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->unsignedBigInteger('pfcmadre_id');
            $table
                ->foreign('pfcmadre_id')
                ->references('id')
                ->on('pfcmadre')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->integer('numero_impronte');
            $table->decimal('peso_impronte', 10,2);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pfcmadre_articolo');
    }
};
