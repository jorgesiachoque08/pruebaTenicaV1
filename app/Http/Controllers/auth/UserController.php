<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UsuariosRoles;
use App\Models\Roles;
use App\Models\Prestadores;
use App\Models\Solicitantes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    protected $roles;
    protected $usuariosRoles;
    protected $prestadores;
    protected $solicitantes;

    public function __construct(Roles $roles , UsuariosRoles $usuariosRoles,Solicitantes $solicitantes,Prestadores $prestadores){
        
        $this->roles = $roles;
        $this->usuariosRoles = $usuariosRoles;
        $this->prestadores = $prestadores;
        $this->solicitantes = $solicitantes;
    }

    public function registrar(Request $request)
    {
        $pmtos = $request->only('usuario','clave');
        $validador = Validator::make($request->all(),[
            'usuario' => ['required', 'string', 'max:255', 'unique:users'],
            'clave' => ['required', 'string', 'min:8'],
        ]);

        if ($validador->fails()) {
            $status = 422;
            $message = "Parametros no validos";
            $data = false;
            $error = $validador->errors();
            return response(compact('status','message','data','error'));
        }else{
            $usuarioRegistrado = User::create([
                'usuario' => $pmtos['usuario'],
                'clave' => bcrypt($pmtos['clave'])
            ]);
            
            $status = 200;
            $message = "Usuario registrado";
            $data = true;
        }
        return response(compact('status','message','data'));
    }


    public function login(Request $request)
    {
        $credenciales = $request->only('usuario','clave');
        $validador = Validator::make($credenciales,[
            'usuario' => ['required', 'string'],
            'clave' => ['required', 'string', 'min:8']
        ]);
        if($validador->fails()){
            $status = 422;
            $message = "Parametros no validos";
            $data = false;
            $error = $validador->errors();
            return response(compact('status','message','data','error'));
        }else{
            $token = JWTAuth::attempt(['usuario' => $credenciales["usuario"], 'password' => $credenciales["clave"]]);
            if($token){
                return $this->respondWithToken($token);
            }else{
                $status = 422;
                $message = "credenciales no validas";
                $data = true;
            }
        }

        return response(compact('status','message','data'));
        
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status'=>200,
            'message'=>"login exitoso",
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'data'=>auth()->user()
            
        ]);
    }

    public function asignarPerfil(Request $request)
    {
        $pmtos = $request->only('rol');
        
        $validador = Validator::make($pmtos,[
            'rol' => ['required', 'integer'],
        ]);

        if ($validador->fails()) {
            $status = 422;
            $message = "Parametros no validos";
            $data = false;
            $error = $validador->errors();
            return response(compact('status','message','data','error'));
        }else{

            if($this->roles->validarRol($pmtos['rol'])){
                
                if($this->usuariosRoles->validarRolUser($pmtos['rol'])){
                    $status = 200;
                    $message = "El rol ya ha sido asignado al usuario";
                    $data = false;
                }else{
                    $insertRolUser = $this->usuariosRoles->asignarRolUser($pmtos['rol']);
                    if($insertRolUser){
                        if($pmtos['rol'] == 1){
                            $data = $this->solicitantes->insert();
                        }else{
                            $data =  $this->prestadores->insert();
                        }
                        $status = 200;
                        $message = "Rol asignado";
                    }else{
                        $status = 200;
                        $message = "Error al asignar el rol";
                        $data = false;
                    }
                }
            }else{
                $status = 200;
                $message = "El rol no existe";
                $data = false;
            }

        }
        return response(compact('status','message','data'));
        
    
    }
}
