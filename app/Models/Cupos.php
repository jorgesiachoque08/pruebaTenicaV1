<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cupos extends Model
{
    use HasFactory;
    public function insert($codCita)
    {
        return DB::table('cupos')->insert(
            ['cod_cita' => $codCita,'usuarios_rolescod_rol'=>1,'cod_usuario_solicitante'=>Auth()->id()]
        );
    }

    public function validarCupo($codCita)
    {
        return DB::table("cupos")
        ->where('cod_cita', $codCita)
        ->where('cod_usuario_solicitante', Auth()->id())
        ->exists();
    }

    public function listarcupos($codCita,$prestador = null)
    {
        if($prestador){
            return DB::table("cupos as c")
            ->join('users as u', 'c.cod_usuario_solicitante', '=', 'u.cod')
            ->rightJoin('citas as ci', 'ci.cod', '=', 'c.cod_cita')
            ->select(DB::raw('c.*,u.cod as idSolicitante,u.usuario as Solicitante'))
            ->where('ci.cod', $codCita)
            ->where('ci.cod_usuario_prestador', Auth()->id())
            ->get();
        }else{
            return DB::table("cupos as c")
            ->join('users as u', 'c.cod_usuario_solicitante', '=', 'u.cod')
            ->rightJoin('citas as ci', 'ci.cod', '=', 'c.cod_cita')
            ->join('prestadores as p', 'p.cod_usuario', '=', 'ci.cod_usuario_prestador')
            ->join('solicitantes_prestadores as sp', 'sp.cod_usuario_prestador', '=', 'p.cod_usuario')
            ->select(DB::raw('c.*,u.cod as idSolicitante,u.usuario as Solicitante'))
            ->where('ci.cod', $codCita)
            ->where('sp.cod_usuario_solicitante', Auth()->id())
            ->get();
        }
    }
}
