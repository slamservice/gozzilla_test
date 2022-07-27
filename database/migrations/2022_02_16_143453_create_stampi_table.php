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
        Schema::create('stampi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codice')->unique();
            $table->string('descrizione');
            $table->string('tipologia');
            $table->integer('allestimento');
            $table->integer('disallestimento');

            $table->string('ubicazione')->nullable();

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
        Schema::dropIfExists('stampi');
    }
};
