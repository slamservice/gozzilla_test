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
        Schema::create('pfc_articolo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('articolo_id');
            $table
                ->foreign('articolo_id')
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
            $table->integer('numero_impronte');
            $table->decimal('peso_impronte', 10,2);
            $table->integer('subtotale')->default(0);
            $table->integer('scorta_per_magazzino')->default(0);
            $table->integer('scorta_a_magazzino')->default(0);
            $table->integer('num_stampate')->default(0);

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
        Schema::dropIfExists('pfc_articolo');
    }
};
