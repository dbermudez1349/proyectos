<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use \Log;
use DB;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(){

        if(!Auth()->user()->hasPermissionTo('ver usuarios'))
        {
            abort(403, 'No tienes acceso a esta seccion.');
        }

        $dep=DB::table('area')
        ->where('estado',1)
        ->get();

        $rol=DB::table('roles')
        ->get();
        return view('gestion_acceso.usuario',[
            "departamento"=>$dep,
            "rol"=>$rol
        ]);
    }

    public function guardar(Request $request){
        
        $transaction=DB::transaction(function() use($request){
            try{ 
                $usuarioExistente = User::where(function($query) use ($request) {
                    $query->where('id_area', $request->departamento)
                        ->orWhere('email', $request->email);
                })
                ->first();

                if ($usuarioExistente) {
                    if($usuarioExistente->estado==1){
                        return response()->json([
                            'error' => true,
                            'mensaje' => 'Ya existe un usuario con ese departamento o correo electrónico.'
                        ]);
                    }else{
                        
                        // Actualizar datos si ya existe
                        $rol=DB::table('roles')
                        ->where('id',$request->rol)
                        ->select('name')->first();
                    
                        $usuarioExistente->update([
                            'email' => $request->email,
                            'id_area' => $request->departamento,
                            'estado' => 1,
                        ]);
                        $usuarioExistente->syncRoles($rol->name);

                        return response()->json([
                            'error'=>false,
                            'mensaje'=>'Usuario actualizado exitosamente'
                        ]);

                    }
                }
                $rol=DB::table('roles')
                ->where('id',$request->rol)
                ->select('name')->first();
               
                $user_new = User::create([
                    'email' => $request->email,
                    'password' => bcrypt('123456'),
                    'id_area' => $request->departamento,
                    'estado' => 1,
                ]);
                $user_new->assignRole($rol->name);

                return response()->json([
                    'error'=>false,
                    'mensaje'=>'Usuario Creado Exitosamente'
                ]);

            }catch (\Throwable $e) {
               
                DB::Rollback();
                Log::error('UsuarioController => guardar => mensaje => '.$e->getMessage().' linea => '.$e->getLine());
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error'
                ]);
                
            }
        });
        return $transaction;
    }

    public function listar(){
        try{
           
            $usuarioConRol = DB::table('users')
                ->join('area', 'area.id', '=', 'users.id_area')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_type', 'App\\Models\\User') // muy importante
                ->select('users.id', 'users.name', 'users.email', 'roles.name as rol','area.descripcion as nombre_area')
                ->get();

            return response()->json([
                'error'=>false,
                'resultado'=>$usuarioConRol
            ]);
        }catch (\Throwable $e) {
            Log::error('UsuarioController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function resetearPassword($idusuario){
        try{
            $existe=User::where('id',$idusuario)
            ->where('estado',1)
            ->first();

            if(is_null($existe)){
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"No se encontró la información del usuario"
                ]);
            }

            $contrasenia_reseteada=$existe->tx_login;

            $existe->password=Hash::make($contrasenia_reseteada);
            $existe->estado=1;
           
            if($existe->save()){
                return response()->json([
                    "error"=>false,
                    "mensaje"=>"La contraseña ha sido reseteada exitosamente"
                ]);
            }else{
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"No se pudo resetear la contraseña"
                ]);
            }
        } catch (\Throwable $th) {
            Log::error('UsuarioController,resetearPassword:' . $th->getMessage()); 
            return response()->json(['error'=>true,'detalle'=>'Incovenientes al procesar la solicitud, intente nuevamente']);

        }
    }

    public function editar($id){
        try{
          
            $usuario = DB::table('users')
            ->join('area', 'area.id', '=', 'users.id_area')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_type', 'App\\Models\\User') // muy importante
            ->select('users.id', 'users.name', 'users.email', 'roles.id as id_rol','area.descripcion as nombre_area','users.id_area')
            ->where('users.id', $id)
            ->first();

                        
            return response()->json([
                'error'=>false,
                'resultado'=>$usuario
            ]);
        }catch (\Throwable $e) {
           
            Log::error('UsuarioController => editar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function actualizar(Request $request, $id){
       
        try{

            $usuarioExistente = User::where('estado', '1')
            ->where(function($query) use ($request) {
                $query->where('id_area', $request->departamento)
                    ->orWhere('email', $request->email);
            })
            ->where('id','!=',$id)
            ->first();

            if ($usuarioExistente) {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'Ya existe otro usuario con ese departamento o correo electrónico.'
                ]);
            }
            $user=User::find($id);
            if ($user) {

                // Actualizar datos si ya existe
                $rol=DB::table('roles')
                ->where('id',$request->rol)
                ->select('name')->first();
               
                $user->update([
                    'email' => $request->email,
                    'id_area' => $request->departamento,
                    'estado' => 1,
                ]);
                $user->syncRoles($rol->name);

                return response()->json([
                    'error'=>false,
                    'mensaje'=>'Usuario actualizado exitosamente'
                ]);

            }

            return response()->json([
                'error'=>true,
                'mensaje'=>'No se pudo actualizar la informacion'
            ]);

        }catch (\Throwable $e) {
            Log::error('UsuarioController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    

    // public function eliminar($id){
    //     $transaction=DB::transaction(function() use($id){
    //         try{
            
    //             $usuario=User::where('id',$id)->first();
    //             $usuario->estado='I';
    //             $usuario->id_actualizado=auth()->user()->id;
    //             $usuario->fe_actualiza=date('Y-m-d H:i:s');
    //             $usuario->save();

    //             $UsuarioPerfil=UsuarioPerfil::where('id_usuario',$usuario->id)->first();
               
    //             //obtenemos el id del usuario logueado (si es el mismo al que se va eliminar lo mandamos al login)
    //             if(auth()->user()->id == $UsuarioPerfil->id_usuario){
    //                 $desloguear="S";
    //             }else{
    //                 $desloguear="N";
    //             }

    //             if($UsuarioPerfil->delete()){
    //                 return response()->json([
    //                     "error"=>false,
    //                     "mensaje"=>"Información eliminada exitosamente",
    //                     "desloguear"=>$desloguear
    //                 ]);
    //             }else{
    //                 return response()->json([
    //                     "error"=>true,
    //                     "mensaje"=>"No se pudo eliminar la información"
    //                 ]);
    //             }
                
    //         }catch (\Throwable $e) {
    //             DB::Rollback();
    //             Log::error('UsuarioController => eliminar => mensaje => '.$e->getMessage());
    //             return response()->json([
    //                 'error'=>true,
    //                 'mensaje'=>'Ocurrió un error, intentelo más tarde'
    //             ]);
                
    //         }
    //     });
    //     return $transaction;
    // }

    public function cambiarClave(Request $request){
      
        try {
            $validator = Validator::make($request->all(), [
                'clave_actual' => 'required|min:6|string|regex:/^[a-zA-Z0-9_\-@$&#.]{6,18}$/',
                'clave_nueva' => 'required|min:6|string|regex:/^[a-zA-Z0-9_\-@$&#.]{6,18}$/',
            ]);
            if ($validator->fails()) {    
                return response()->json([
                    'error' => true, 
                    'detalle' => 'Contraseña debe tener mínimo 6 caracteres'
                ]);

            }
            $usuario= auth()->User();

            if (Hash::check($request['clave_actual'], $usuario->password)){
            
                if($request['clave_nueva']==$request['clave_nueva_confirm']){

                    if (Hash::check($request['clave_nueva'], $usuario->password)){

                        return response()->json([
                            'error'=>true,
                            'detalle' => 'La nueva contraseña no puede ser igual a la anterior'
                        ]);

                    }else{

                        $usuario->password=bcrypt($request['clave_nueva']);
                        if($usuario->save()){
                            return response()->json(['error'=>false,'detalle'=>'Contraseña actualizada exitosamente']);
                        }
                        else{
                            return response()->json(['error'=>true,'detalle'=>'Error, inténtelo nuevamente']);
                        }

                    }
                    
                }else{
                    return response()->json(['error'=>true,'detalle'=>'Las contraseñas no coinciden']);
                } 
            }else{
                return response()->json(['error'=>true,'detalle'=>'La contraseña actual ingresada no es la correcta por favor verificar']);
            }

        } catch (\Throwable $th) {
            Log::error('UsuarioController,CambiarContrasenia:' . $th->getMessage()); 
            return response()->json(['error'=>true,'detalle'=>'Incovenientes al procesar la solicitud, intente nuevamente']);

        }
    }

   

    // public function bodegaUsuario($id){
    //     try{
    //         $bodegas=Bodega::where('estado','=',1)->get();
    //         foreach($bodegas as $key=> $data){
    //             $verificaBodega=BodegaUsuario::where('idusuario',$id)
    //             ->where('idbodega',$data->idbodega)->first();
    //             if(!is_null($verificaBodega)){
    //                 $bodegas[$key]->accesoPerm="S";
    //             }else{
    //                 $bodegas[$key]->accesoPerm="N";
    //             }
    //         }
            
    //         return response()->json([
    //             'error'=>false,
    //             'resultado'=>$bodegas
    //         ]);
               
    //     }catch (\Throwable $e) {
    //         Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage()." Linea =>".$e->getLine());
    //         return response()->json([
    //             'error'=>true,
    //             'mensaje'=>'Ocurrió un error, intentelo más tarde'
    //         ]);
            
    //     }
    // }


    // public function mantenimientoBodegaUser($idbodega, $tipo, $iduser){
       
    //     try{
    //         //agregamos
    //         if($tipo=="A"){
              
    //             $ultimo=BodegaUsuario::get()->last();
    //             if(is_null($ultimo)){
    //                $suma=1;
    //             }else{
    //                 $suma=$ultimo->idbodega_usuario+1;
    //             }
    //             $bodega_user= new BodegaUsuario();
    //             $bodega_user->idbodega_usuario=$suma;
    //             $bodega_user->idusuario=$iduser;
    //             $bodega_user->idbodega=$idbodega;
    //             $bodega_user->save();
    //             return response()->json([
    //                 'error'=>false,
    //                 'mensaje'=>'Información registrada exitosamente'
    //             ]);
    //         }else{
    //             //lo quitamos
    //             $quitar=BodegaUsuario::where('idbodega',$idbodega)
    //             ->where('idusuario',$iduser)->first();
    //             $quitar->delete();
    //             return response()->json([
    //                 'error'=>false,
    //                 'mensaje'=>'Información quitada exitosamente'
    //             ]);
    //         }
           

    //     }catch (\Throwable $e) {
    //         Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage()." Linea =>".$e->getLine());
    //         return response()->json([
    //             'error'=>true,
    //             'mensaje'=>'Ocurrió un error, intentelo más tarde'
    //         ]);
            
    //     }
    // }

}
