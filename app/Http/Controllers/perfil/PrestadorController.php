<?php

namespace App\Http\Controllers\perfil;

use App\Http\Controllers\Controller;
use App\Models\Prestadores;
use App\Models\UsuariosRoles;
use App\Models\Citas;
use App\Models\Cupos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrestadorController extends Controller
{
    
    protected $usuariosRoles;
    protected $prestadores;
    protected $citas;
    protected $cupos;

    public function __construct(Cupos $cupos,UsuariosRoles $usuariosRoles,Prestadores $prestadores,Citas $citas){
        
        $this->usuariosRoles = $usuariosRoles;
        $this->prestadores = $prestadores;
        $this->citas = $citas;
        $this->cupos = $cupos;
    }

    public function crearCita(Request $request)
    {
        $pmtos = $request->only('descripcion','cupos_totales','fecha');
        $validador = Validator::make($pmtos,[
            'descripcion' => ['required', 'string', 'max:255'],
            'cupos_totales' => ['required', 'integer'],
            'fecha' => ['required', 'date_format:"Y-m-d"'],
        ]);

        if ($validador->fails()) {
            $status = 422;
            $message = "Parametros no validos";
            $data = false;
            $error = $validador->errors();
            return response(compact('status','message','data','error'));
        }else{
            if($this->citas->validarCita($pmtos['fecha'])){
                $data = false;
                $status = 200;
                $message = "Ya programaste una cita para esta fecha";
            }else{
                $cita = ['descripcion'=>$pmtos['descripcion'],'cupos_totales'=>$pmtos['cupos_totales'],'cupos_disponibles'=>$pmtos['cupos_totales'],'cod_usuario_prestador'=>Auth()->id(),'fecha'=>$pmtos['fecha']];
                $data = $this->citas->insertCita($cita);
                $status = 200;
                $message = "La cita ha sido creada";
            }
        }

        return response(compact('status','message','data'));
    }
    public function listarCuposCita(Request $request)
    {
        
            $cupos = $this->cupos->listarcupos($request->codCita,true);
            if(count($cupos) > 0){
                if($cupos[0]->cod_cita){
                    $data = $cupos;
                    $status = 200;
                    $message = "Cupos apartados de tu cita";
                }else{
                    $data = [];
                    $status = 200;
                    $message = "no tiene cupos apartados";
                }
                
            }else{
                $data = $cupos;
                $status = 200;
                $message = "la cita no la creaste tu";
            }
        
        return response(compact('status','message','data'));
        
    }

   
}
