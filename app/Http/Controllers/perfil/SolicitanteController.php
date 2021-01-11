<?php

namespace App\Http\Controllers\perfil;

use App\Http\Controllers\Controller;
use App\Models\Citas;
use App\Models\Solicitantes;
use App\Models\Prestadores;
use App\Models\SolicitantesPrestadores;
use App\Models\UsuariosRoles;
use App\Models\Cupos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SolicitanteController extends Controller
{
    protected $usuariosRoles;
    protected $solicitante;
    protected $solicitantePrestador;
    protected $prestadores;
    protected $citas;
    protected $cupos;

    public function __construct(Cupos $cupos,Citas $citas,Prestadores $prestadores,UsuariosRoles $usuariosRoles,Solicitantes $solicitante,SolicitantesPrestadores $solicitantePrestador){
        
        $this->usuariosRoles = $usuariosRoles;
        $this->solicitante = $solicitante;
        $this->solicitantePrestador = $solicitantePrestador;
        $this->prestadores = $prestadores;
        $this->citas = $citas;
        $this->cupos = $cupos;
    }

    public function subscribirse(Request $request)
    {
        
            $pmtos = $request->only('IdUserPrestador');
            $validador = Validator::make($pmtos,[
                'IdUserPrestador' => ['required', 'integer'],
            ]);
            if ($validador->fails()) {
                $status = 422;
                $message = "Parametros no validos";
                $data = false;
                $error = $validador->errors();
                return response(compact('status','message','data','error'));
            }else{
                if($this->prestadores->validarPrestador($pmtos['IdUserPrestador'])){
                    if($this->solicitantePrestador->validarSubscripcion($pmtos['IdUserPrestador'])){
                        $status = 200;
                        $message = "Ya te encuentras subscrito a este prestador";
                        $data = false;
                    }else{
                        $data = $this->solicitantePrestador->subscribirse(["cod_usuario_solicitante"=>Auth()->id(),"cod_usuario_prestador"=>$pmtos['IdUserPrestador']]);
                        $status = 200;
                        $message = "La subscripcion fue creada";
                    }
                }else{
                    $status = 200;
                    $message = "Este Usuario no es prestador o no existe";
                    $data = false;
                }
            }

        return response(compact('status','message','data'));
    }

    public function apartarCupo(Request $request)
    {   
        $pmtos = $request->only('codCita');
        $validador = Validator::make($pmtos,[
            'codCita' => ['required', 'integer'],
        ]);

        if ($validador->fails()) {
            $status = 422;
            $message = "Parametros no validos";
            $data = false;
            $error = $validador->errors();
            return response(compact('status','message','data','error'));
        }else{
            $cita = $this->citas->listarCita($pmtos['codCita']);
            if($cita){
                if($cita->cupos_disponibles > 0){
                    if($this->cupos->validarCupo($pmtos['codCita'])){
                        $status = 200;
                        $message = "Ya has apartado el cupo para esta cita";
                        $data = false;
                    }else{
                        if($this->cupos->insert($pmtos['codCita'])){
                            $this->citas->decrementCupoDisponibles($pmtos['codCita']);
                            $status = 200;
                            $message = "Se ha apartado el cupo";
                            $data = true;
                        }else{
                            $status = 200;
                            $message = "Error al apartar el cupo";
                            $data = false;
                        }
                        
                    }
                    
                    
                }else{
                    $status = 200;
                    $message = "No hay cupos disponibles para esta cita";
                    $data = false;
                }
            }else{
                $status = 200;
                $message = "la cita no existe o no se ha subcrito al prestador de la cita";
                $data = false;
            }
        }
        
        return response(compact('status','message','data'));
            
    }
    public function listarCuposCita(Request $request)
    {
        
            $cupos = $this->cupos->listarcupos($request->codCita);
            if(count($cupos) > 0){
                if($cupos[0]->cod_cita){
                    $data = $cupos;
                    $status = 200;
                    $message = "Cupos apartados de la cita";
                }else{
                    $data = [];
                    $status = 200;
                    $message = "La cita no tiene cupos apartados";
                }
                
            }else{
                $data = $cupos;
                $status = 200;
                $message = "No estas subscrito al creador de la cita para poder ver los cupos";
            }
        
        return response(compact('status','message','data'));
        
    }

    public function listarPrestadores(Request $request)
    {
        
            $prestadores = $this->prestadores->listarPrestadores();
            if(count($prestadores) > 0){
                $data = $prestadores;
                $status = 200;
                $message = "ok";
                
                
            }else{
                $data = [];
                $status = 200;
                $message = "no se encotraron resultados";
            }
        
        return response(compact('status','message','data'));
        
    }

    public function listarCitas(Request $request)
    {
        
            $citas = $this->citas->listarCitas();
            if(count($citas) > 0){
                $data = $citas;
                $status = 200;
                $message = "ok";
            }else{
                $data = [];
                $status = 200;
                $message = "no se encotraron resultados";
            }
        
        return response(compact('status','message','data'));
        
    }
}
