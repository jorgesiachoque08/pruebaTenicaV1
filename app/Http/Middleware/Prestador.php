<?php

namespace App\Http\Middleware;

use App\Models\Prestadores;
use Closure;
use Illuminate\Http\Request;

class Prestador
{
    protected $prestadores;

    public function __construct(Prestadores $prestadores){
        
        $this->prestadores = $prestadores;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$this->prestadores->validarPrestador(Auth()->id())){
            return response(["status"=>403,"message"=>"No tienes el rol prestador asignado para realizar esta petición","data"=>false]);
        }else{
            return $next($request);
        }
    }
}
