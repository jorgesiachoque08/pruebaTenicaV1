<?php

namespace App\Http\Middleware;

use App\Models\Solicitantes;
use Closure;
use Illuminate\Http\Request;

class Solicitante
{
    protected $solicitante;

    public function __construct(Solicitantes $solicitante){
        
        $this->solicitante = $solicitante;
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

        if(!$this->solicitante->validarSolicitante(Auth()->id())){
            return response(["status"=>403,"message"=>"No tienes el rol solicitante asignado para realizar esta petición","data"=>false]);
        }else{
            return $next($request);
        }
        
    }
}
