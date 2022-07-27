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
        Schema::table('movimenti', function (Blueprint $table) {
            $table->unsignedBigInteger('lotto_id')->nullable()->after('articolo_id');
            $table
            ->foreign('lotto_id')
            ->references('id')
            ->on('lotti')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movimenti', function (Blueprint $table) {
            $table->dropColumn('lotto_id');
        });
    }
};
