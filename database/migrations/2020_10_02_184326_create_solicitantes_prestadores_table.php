<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitantesPrestadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitantes_prestadores', function (Blueprint $table) {
            $table->integer('cod_usuario_prestador')->unsigned();
            $table->integer('cod_usuario_solicitante')->unsigned();
            $table->foreign('cod_usuario_prestador')->references('cod_usuario')->on('prestadores');
            $table->foreign('cod_usuario_solicitante')->references('cod_usuario')->on('solicitantes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitantes_prestadores');
    }
}
