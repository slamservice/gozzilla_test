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
            $table->string('descrizione')->nullable()->after('qta_scarico');
            $table->string('tipo_movimento')->default('manuale')->after('tipo');
            $table->boolean('inventariato')->default(0)->after('tipo');
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
            $table->dropColumn('descrizione');
            $table->dropColumn('tipo_movimento');
            $table->dropColumn('inventariato');
        });
    }
};
