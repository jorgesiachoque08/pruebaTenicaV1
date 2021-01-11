<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Roles extends Model
{
    use HasFactory;



    public function validarRol($idRol)
    {
        return DB::table("roles")
            ->where('cod', $idRol)
            ->exists();
    }


}
