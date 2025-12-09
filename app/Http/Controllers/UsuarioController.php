<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-usuario|crear-usuario|editar-usuario|borrar-usuario', ['only' => ['index']]);
        $this->middleware('permission:crear-usuario', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-usuario', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-usuario', ['only' => ['destroy']]);
    }

    public function index()
    {
        // Paginamos los usuarios activos
        $usuarios = User::where('estado', true)->paginate(5);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('usuarios.crear', compact('roles'));
    }

    public function show()
    {
        //
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.same' => 'Las contraseñas no coinciden.',
            'roles.required' => 'Debes de asignar al menos un rol.',
            'url_image.image' => 'El archivo debe ser una imagen.',
            'url_image.mimes' => 'La imagen debe ser de tipo: jpg, jpeg, png.',
            'url_image.max' => 'La imagen no debe pesar más de 2MB.',
        ];

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'url_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
        $input['estado'] = true;
        
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Laravel detecta automáticamente que el parámetro de ruta es un ID encriptado
     * y usa tu método 'resolveRouteBinding' del modelo User para desencriptarlo.
     * La variable $usuario ya llega como el objeto real.
     */
    public function edit(User $usuario)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $usuario->roles->pluck('name', 'name')->all();
        return view('usuarios.editar', ['user' => $usuario, 'roles' => $roles, 'userRole' => $userRole]);
    }

    public function update(Request $request, User $usuario)
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
            // Usamos $usuario->id porque la variable ya fue resuelta por Laravel
            'email' => ['required', 'email', Rule::unique('users')->ignore($usuario->id)],
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'url_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], $messages);

        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        if ($request->hasFile('url_image')) {
            $imageFile = $request->file('url_image');

            if ($imageFile->isValid()) {
                if ($usuario->url_image && Storage::disk('public')->exists($usuario->url_image)) {
                    Storage::disk('public')->delete($usuario->url_image);
                }
                $path = $imageFile->store('usuarios/imagenes', 'public');
                $input['url_image'] = $path;
            }
        }

        $usuario->update($input);
        $usuario->syncRoles($request->input('roles'));

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function changeStatus(User $usuario)
    {
        try {
            if ($usuario->hasRole('Administrador') && $usuario->id == 1) {
                return response()->json(['message' => 'No se puede desactivar al Super Admin'], 403);
            }

            $nuevoEstado = !$usuario->estado;
            $actualizado = $usuario->update(['estado' => $nuevoEstado]);

            if ($actualizado) {
                return response()->json([
                    'message' => 'Estado actualizado correctamente',
                    'nuevo_estado' => $nuevoEstado
                ]);
            } else {
                return response()->json(['message' => 'No se pudo guardar el cambio en BD'], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error crítico: ' . $e->getMessage()], 500);
        }
    }

    /**
     * AQUÍ ESTÁ LA CORRECCIÓN PRINCIPAL
     * Si recibes un ID encriptado por POST/GET (no por URL amigable),
     * debes desencriptarlo manualmente usando la lógica del modelo.
     */
    public function editHidden(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required',
        ]);

        // Instanciamos un User vacío para acceder a su método de resolución
        // Esto toma el ID encriptado (ej: 75bcd14) y encuentra el usuario real
        $usuario = (new User)->resolveRouteBinding($request->input('id_usuario'));

        $roles = Role::pluck('name', 'name')->all();
        $userRole = $usuario->roles->pluck('name', 'name')->all();

        return view('usuarios.editar', ['user' => $usuario, 'roles' => $roles, 'userRole' => $userRole]);
    }

    public function getInactiveUsers()
    {
        if(request()->ajax()) {
            $inactiveUsers = User::where('estado', 0)->get(['id', 'name', 'email', 'url_image']);

            // Agregamos el ID encriptado (uuid) para que el frontend lo use en los botones
            $inactiveUsers->each(function ($user) {
                $user->uuid = $user->getRouteKey();
            });

            return response()->json($inactiveUsers);
        }

        return abort(404);
    }

    public function destroy(User $usuario)
    {
        try {
            if ($usuario->hasRole('Administrador')) {
                return response()->json(['message' => 'No se puede eliminar un Administrador'], 403);
            }

            // Soft delete lógico (cambiar estado)
            $usuario->update(['estado' => false]);

            return response()->json(['message' => 'Usuario desactivado correctamente']);
        } catch (\Exception $e) {
            Log::error("Error eliminando usuario ID: {$usuario->id}. Error: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al eliminar'], 500);
        }
    }
}