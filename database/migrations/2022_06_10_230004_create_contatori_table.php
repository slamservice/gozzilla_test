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
        Schema::create('contatori', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('maschera');
            $table->integer('valore');
            $table->boolean('cambio_anno')->default(0);
            $table->string('anno')->default('');

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
        Schema::dropIfExists('contatori');
    }
};
