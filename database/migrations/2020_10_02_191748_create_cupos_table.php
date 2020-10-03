<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupos', function (Blueprint $table) {
            $table->integer('usuarios_rolescod_rol');
            $table->integer('cod_usuario_solicitante')->unsigned();;
            $table->integer('cod_cita')->unsigned();
            $table->foreign('cod_usuario_solicitante')->references('cod_usuario')->on('solicitantes');
            $table->foreign('cod_cita')->references('cod')->on('citas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cupos');
    }
}
