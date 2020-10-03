<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Citas extends Model
{
    use HasFactory;


    public function insertCita($cita)
    {
        return DB::table('citas')->insert($cita);
    }

    public function validarCita($fecha)
    {
        return DB::table("citas")
        ->where('cod_usuario_prestador', Auth()->id())
        ->where('fecha', $fecha)
        ->exists();
    }

    public function listarCita($codCita)
    {
        return DB::table("citas as c")
        ->join('prestadores as p', 'c.cod_usuario_prestador', '=', 'p.cod_usuario')
        ->join('solicitantes_prestadores as sp', 'p.cod_usuario', '=', 'sp.cod_usuario_prestador')
        ->select(DB::raw('c.*'))
        ->where('c.cod', $codCita)
        ->where('sp.cod_usuario_solicitante', Auth()->id())
        ->first();
    }

    public function decrementCupoDisponibles($codCita)
    {
        return DB::table('citas')->where('cod', $codCita)->decrement('cupos_disponibles', 1);
    }


}
