<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Solicitantes extends Model
{
    use HasFactory;

    public function insert()
    {
        return DB::table('solicitantes')->insert(
            ['cod_usuario' => Auth()->id()]
        );
    }

    public function validarSolicitante($idUserSolic)
    {
        return DB::table("solicitantes")
        ->where('cod_usuario', $idUserSolic)
        ->exists();
    }
}
