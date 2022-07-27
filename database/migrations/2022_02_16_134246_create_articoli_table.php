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
        Schema::create('articoli', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codice')->unique();
            $table->string('descrizione')->nullable();
            $table->string('tipologia');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->string('colore_master')->nullable();
            $table->boolean('condizionato')->default(0);
            $table->integer('condizionamento_temperatura')->nullable();
            $table->integer('condizionamento_tempo')->nullable();
            $table->unsignedBigInteger('famiglia_polimero_id')->nullable();
            $table->longText('nota')->nullable();
            //$table->string('model_type');
            $table->index('codice');
            $table->index('cliente_id');
            $table->index('tipologia');

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
        Schema::dropIfExists('articoli');
    }
};
