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
        Schema::table('pfcmadre', function (Blueprint $table) {
            $table->boolean('serve_robot')-> default(0)->after('tempo_ciclo');
            $table->boolean('stampaggio_automatico')->default(1)->after('serve_robot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pfcmadre', function (Blueprint $table) {
            $table->dropColumn(['serve_robot', 'stampaggio_automatico']);
        });
    }
};
