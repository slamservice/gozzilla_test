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
        Schema::create('famiglie_polimero', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sigla')->unique();
            $table->string('descrizione');

            $table->index('sigla');

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
        Schema::dropIfExists('famiglie_polimero');
    }
};
