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
        Schema::create('magazzini', function (Blueprint $table) {
            $table->id();
            $table->string('codice',20)->unique();
            $table->string('descrizione');
            $table->boolean('attivo')->default(1);
            $table->date('attivato_il');
            $table->date('disattivato_il')->nullable();
            $table->longText('nota')->nullable();
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
        Schema::dropIfExists('magazzini');
    }
};
