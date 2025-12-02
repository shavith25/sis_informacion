<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-usuario|crear-usuario|editar-usuario|borrar-usuario',['only'=>['index']]);
        $this->middleware('permission:crear-usuario',['only'=>['create','store']]);
        $this->middleware('permission:editar-usuario',['only'=>['edit','update']]);
        $this->middleware('permission:borrar-usuario',['only'=>['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios =User::paginate(5);
        return view('usuarios.index',compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles= Role::pluck('name','name')->all();
        return view('usuarios.crear',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.same' => 'Las contraseñas no coinciden.',
            'roles.nullable' => 'El campo de roles no puede ser nulo.',
            'url_image.image' => 'El archivo debe ser una imagen.',
            'url_image.mimes' => 'La imagen debe ser de tipo: jpg, jpeg, png.',
            'url_image.max' => 'La imagen no debe pesar más de 2MB.',
            ];

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'nullable',
            'url_image' => 'nullable|image|mimes:jpg,jpeg,png|max:8048',
        ], $messages);
        $input = $request->all();
         if ($request->hasFile('url_image')) {
            $imageFile = $request->file('url_image');

            if ($imageFile->isValid()) {
                $path = $imageFile->store('usuarios/imagenes', 'public');

                
                $input['url_image'] = $path;
            }
        }
        
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('usuarios.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::find($id);
        $roles=Role::pluck('name','name')->all();
        $userRole=$user->roles->pluck('name','name')->all();
        return view('usuarios.editar',compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.same' => 'Las contraseñas no coinciden.',
            'roles.required' => 'El campo de roles no puede ser nulo.',
            'url_image.image' => 'El archivo debe ser una imagen.',
            'url_image.mimes' => 'La imagen debe ser de tipo: jpg, jpeg, png.',
            'url_image.max' => 'La imagen no debe pesar más de 2MB.',
        ];

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'url_image' => 'nullable|image|mimes:jpg,jpeg,png|max:8048',
        ], $messages);

        $input=$request->all();
        if(!empty($input['password'])){
            $input['password']=Hash::make($input['password']);
        }else{
            $input= Arr::except($input, array('password'));
        }
        $user=User::find($id);
         if ($request->hasFile('url_image')) {
            $imageFile = $request->file('url_image');

            if ($imageFile->isValid()) {
                if ($user->url_image && Storage::disk('public')->exists($user->url_image)) {
                    Storage::disk('public')->delete($user->url_image);
                }

                $path = $imageFile->store('usuarios/imagenes', 'public');
                $input['url_image'] = $path;
            }
        }
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));
        return redirect()->route('usuarios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(User $usuario)
    {
        try {
            if ($usuario->hasRole('Administrador')) {
                return response()->json(['message' => 'No se puede cambiar el estado de un Administrador'], 403);
            }

            $usuario->estado = !$usuario->estado;
            $usuario->save();

            return response()->json(['message' => 'Estado actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el estado'], 500);
        }
    }

    public function destroy(User $usuario)
    {
        try {
            if ($usuario->hasRole('Administrador')) {
                return response()->json(['message' => 'No se puede eliminar un Administrador'], 403);
            }

            $usuario->delete();
            return response()->json(['message' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el usuario'], 500);
        }
    }
}
