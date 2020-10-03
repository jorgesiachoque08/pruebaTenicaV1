<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
                $table->increments('cod');
                $table->string('descripcion',255);
                $table->integer('cupos_totales');
                $table->integer('cupos_disponibles');
                $table->integer('cod_usuario_prestador')->unsigned();
                $table->foreign('cod_usuario_prestador')->references('cod_usuario')->on('prestadores');
                $table->date('fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas');
    }
}
