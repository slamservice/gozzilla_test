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
        Schema::create('movimenti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magazzino_id')->constrained('magazzini');
            $table->foreignId('articolo_id')->constrained('articoli');

            //$table->string('lotto');
            //$table->date('data_lotto');

            //tipo: carico, scarico
            $table->string('tipo',15);
            $table->integer('qta_carico')->default(0);
            $table->integer('qta_scarico')->default(0);

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
        Schema::dropIfExists('movimenti');
    }
};
