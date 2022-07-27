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
        Schema::create('intervento_elemento', function (Blueprint $table) {
            $table->unsignedBigInteger('intervento_id');
            $table->unsignedBigInteger('elemento_id');
            $table->string('elemento'); //pressa, stampo, essicatore, macchinario

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
        Schema::dropIfExists('intervento_elemento');
    }
};
