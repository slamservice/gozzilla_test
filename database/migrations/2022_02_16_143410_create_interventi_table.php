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
        Schema::create('interventi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('data');
            $table->string('descrizione');
            $table->string('elemento'); //pressa, stampo, essicatore, macchinario
            $table->unsignedBigInteger('elemento_id');

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
        Schema::dropIfExists('interventi');
    }
};
