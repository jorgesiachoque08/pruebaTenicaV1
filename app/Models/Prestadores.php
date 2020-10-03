<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Prestadores extends Model
{
    use HasFactory;

    public function insert()
    {
        return DB::table('prestadores')->insert(
            ['cod_usuario' => Auth()->id()]
        );
    }

    public function validarPrestador($idUserPrest)
    {
        return DB::table("prestadores")
        ->where('cod_usuario', $idUserPrest)
        ->exists();
    }
}
