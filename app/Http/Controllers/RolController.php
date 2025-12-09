<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class RolController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-rol|crear-rol|editar-rol|borrar-rol', ['only' => ['index']]);
        $this->middleware('permission:crear-rol', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-rol', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-rol', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::paginate(5);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissionsByModule = $this->groupPermissionsByModule();
        return view('roles.crear', compact('permissionsByModule'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'nullable|array', 
        ]);
    
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->input('name')]);
    
            $permission = $request->input('permission', []);
            $permission = array_map('intval', $permission); // Asegurar enteros

            $role->syncPermissions($permission);

            DB::commit();
    
            return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('roles.index')->with('error', 'Error: El nombre del rol ya existe.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }
    
    public function show($id)
    {
        // No implementado
    }

    /**
     * Edición usando ID encriptado con Laravel Crypt
     */
    public function edit($token)
    {
        try {
            // 1. Desencriptamos el ID
            $idReal = Crypt::decryptString($token);
            
            // 2. Buscamos el rol
            $role = Role::findOrFail($idReal);
        } catch (DecryptException $e) {
            // Si el token es inválido o manipulado
            abort(404);
        } catch (\Exception $e) {
            abort(404);
        }

        $permissionsByModule = $this->groupPermissionsByModule();
        
        // Obtener permisos del rol
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $idReal)
            ->pluck('role_has_permissions.permission_id')
            ->toArray(); // Convertir a array simple para in_array()

        return view('roles.editar', compact('role', 'permissionsByModule', 'rolePermissions'));
    }

    /**
     * Actualización usando ID encriptado
     */
    public function update(Request $request, $token)
    {
        try {
            // 1. Desencriptar ID
            $idReal = Crypt::decryptString($token);
            $role = Role::findOrFail($idReal);
        } catch (DecryptException $e) {
            abort(404);
        }

        // Validación (usando el ID real desencriptado para la regla unique)
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $idReal,
            'permission' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $role->name = $request->input('name');
            $role->save();

            $permission = $request->input('permission', []);
            $permission = array_map('intval', $permission);

            $role->syncPermissions($permission);

            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }
    
    public function destroy($token)
    {
        try {
            // Intentar desencriptar (si viene encriptado desde la vista index)
            // Si en index.blade.php usas el ID normal, esto fallará y caerá en el catch.
            // Para robustez, soportamos ambos casos si es necesario, pero lo ideal es encriptar siempre.
            try {
                $idReal = Crypt::decryptString($token);
            } catch (\Exception $e) {
                // Si falla la desencriptación, asumimos que es un ID numérico directo (legacy support)
                $idReal = $token; 
            }

            $role = Role::findOrFail($idReal);

            // Validar roles protegidos
            if (in_array($role->name, ['Administrador', 'Super Admin'])) {
                return redirect()->route('roles.index')
                    ->with('error', 'No se puede eliminar un rol de Administrador.');
            }

            if ($role->users()->count() > 0) {
                return redirect()->route('roles.index')
                    ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
            }

            // Eliminar permisos asociados antes de borrar el rol
            $role->syncPermissions([]); 
            $role->delete();

            return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Error al eliminar el rol.');
        }
    }

    private function groupPermissionsByModule()
    {
        return Permission::all()->groupBy(function ($permission) {
            $nameParts = explode('-', $permission->name);
            $moduleName = $nameParts[1] ?? 'General';

            // Mapa de nombres para mejor visualización
            $map = [
                'mapa' => 'Mapas', 'areas' => 'Mapas',
                'limites' => 'Límites', 'departamento' => 'Límites', 
                'provincia' => 'Límites', 'municipio' => 'Límites',
                'panel' => 'Paneles', 'ayuda' => 'Paneles',
                'comentarios' => 'Comentarios', 'media' => 'Media Comunidad',
                'reportes' => 'Reportes Ambientales', 'usuario' => 'Usuarios',
                'rol' => 'Roles'
            ];

            return $map[$moduleName] ?? ucfirst($moduleName);
        });
    }
}