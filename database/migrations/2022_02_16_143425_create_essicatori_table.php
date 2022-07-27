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
        Schema::create('essicatori', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codice')->unique();
            $table->string('descrizione');
            $table->unsignedBigInteger('fornitore_id');

            $table->string('matricola')->nullable();
            $table->integer('portata')->nullable();

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
        Schema::dropIfExists('essicatori');
    }
};
