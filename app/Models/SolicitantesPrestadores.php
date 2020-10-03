<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SolicitantesPrestadores extends Model
{
    use HasFactory;

    public function Subscribirse($subcripcion)
    {
        return DB::table('solicitantes_prestadores')->insert($subcripcion);
    }

    public function validarSubscripcion($idUserPrest)
    {
        return DB::table("solicitantes_prestadores")
        ->where('cod_usuario_solicitante', auth()->id())
        ->where('cod_usuario_prestador', $idUserPrest)
        ->exists();
    }
}
