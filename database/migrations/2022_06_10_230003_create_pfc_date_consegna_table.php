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
        Schema::create('pfc_date_consegna', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pfc_articolo_id');
            $table
                ->foreign('pfc_articolo_id')
                ->references('id')
                ->on('pfc_articolo')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->date('data_consegna');
            $table->integer('qta')->default(0);

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
        Schema::dropIfExists('pfc_date_consegna');
    }
};
