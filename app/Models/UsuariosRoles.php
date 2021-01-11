<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class UsuariosRoles extends Model
{
    use HasFactory;

    public function validarRolUser($idRol)
    {
        return DB::table("usuarios_roles")
        ->where('cod_rol', $idRol)
        ->where('cod_usuario', auth()->id())
        ->exists();
    }

    public function asignarRolUser($idRol)
    {
        return DB::table('usuarios_roles')->insert(
            ['cod_rol' => $idRol, 'cod_usuario' => Auth()->id()]
        );
    }

    public function listarRolesUser()
    {
        return DB::table('usuarios_roles as ur')
        ->join('roles as r', 'ur.cod_rol', '=', 'r.cod')
        ->select(DB::raw('r.*'))
        ->where('ur.cod_usuario', Auth()->id())
        ->get();
    }

    
}
