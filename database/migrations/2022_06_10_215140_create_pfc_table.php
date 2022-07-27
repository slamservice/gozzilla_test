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
        Schema::create('pfc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pfcmadre_id');

            $table->string('codice')->unique();
            //stato ??
            $table->foreignId('cliente_id')->constrained('clienti');

            $table->string('num_ordine', 100)->nullable();

            //stampo start
            $table->foreignId('stampo_id')->constrained('stampi');
            $table->string('stampo_ubicazione')->nullable();
            $table->boolean('stampo_condizionamento')->default(0);
            //fisso, mobile, fisso+mobile,fisso/mobile
            $table->string('stampo_tipo_condizionamento')->nullable();
            //frigo, centralina
            $table->string('stampo_subtipo_condizionamento')->nullable();
            $table->integer('stampo_numero_linee')->nullable();
            $table->integer('stampo_temperatura')->nullable();
            $table->string('stampo_subtipo_condizionamento_fm')->nullable();
            $table->integer('stampo_numero_linee_fm')->nullable();
            $table->integer('stampo_temperatura_fm')->nullable();

            //polimero
            $table->bigInteger('polimero_id');
            $table->boolean('polimero_condizionamento')->default(0);
            $table->integer('polimero_temperatura')->nullable();
            $table->integer('polimero_tempo')->nullable();

            $table->decimal('peso_matarozza',5,2)->default(0);
            $table->decimal('peso_stampata',5,2)->default(0);
            $table->integer('tempo_ciclo')->default(0);
            $table->boolean('serve_robot')-> default(0);
            $table->boolean('stampaggio_automatico')->default(1);

            //stampo end
            $table->integer('percentuale_materiale_vergine')->default(0);
            $table->integer('percentuale_materiale_macinato')->default(0);
            $table->integer('numero_inserti_necessari')->default(0);
            $table->boolean('plus_fasi_stampaggio')->default(0);

            $table->string('colore')->nullable();
            $table->index('codice');

            $table->longText('nota')->nullable();

            $table->integer('totali')->default(0);

            $table->string('verifica_rapporto_codici')->nullable();

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
        Schema::dropIfExists('pfc');
    }
};
