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
        Schema::create('presse', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codice')->unique();
            $table->string('descrizione');
            $table->integer('tonnellaggio');
            $table->unsignedBigInteger('fornitore_id');

            $table->string('matricola')->nullable();
            $table->integer('diametro_vite');
            $table->integer('grammatura_stampaggio');
            $table->integer('passaggio_colonne_altezza')->nullable();
            $table->integer('passaggio_colonne_larghezza')->nullable();


            $table->index('codice');

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
        Schema::dropIfExists('presse');
    }
};
