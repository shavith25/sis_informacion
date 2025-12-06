<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
        $roles =Role::paginate(5);
        return view('roles.index',compact('roles'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissionsByModule = $this->groupPermissionsByModule();
        return view('roles.crear', compact('permissionsByModule'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

            $role->syncPermissions($permission);

            DB::commit();
    
            return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('roles.index')->with('error', 'Error al crear el rol, el nombre del rol ya está en uso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error inesperado al crear el rol.');
        }
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
    public function edit($token)
    {
        try {
            $partes = explode('x', $token);

            if (count($partes) != 2) abort(404);

            $aleatorio = hexdec($partes[0]);
            $mezcla = hexdec($partes[1]);

            $idReal = $mezcla ^ $aleatorio;

            $role = Role::findOrFail($idReal);
        } catch (\Exception $e) {
            abort(404);
        }

        $permissionsByModule = $this->groupPermissionsByModule();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $idReal)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('roles.editar', compact('role', 'permissionsByModule', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $token)
    {
        $partes = explode('x', $token);

        if (count($partes) != 2) abort(404);

        $aleatorio = hexdec($partes[0]);
        $mezcla = hexdec($partes[1]);
        $idReal = $mezcla ^ $aleatorio;

        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $idReal,
            'permission' => 'nullable|array',
        ]);

        $role = Role::findOrFail($idReal);
        $role->name = $request->input('name');
        $role->save();

        DB::beginTransaction();
        try {
            $role->update(['name' => $request->input('name')]);

            $permission = $request->input('permission', []);
            $permission = array_map('intval', $permission);

            $role->syncPermissions($permission);

            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'Error al actualizar el rol, el nombre del rol ya está en uso.')->withInput();
            }
            return redirect()->back()->with('error', 'Error SQL:' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al actualizar el rol: ' . $e->getMessage());
        }
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'El rol no se puede eliminar porque está asignado a uno o más usuarios.');
        }

        $role->syncPermissions([]);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }

    private function groupPermissionsByModule()
    {
        return Permission::all()->groupBy(function ($permission) {
            $nameParts = explode('-', $permission->name);
            $moduleName = $nameParts[1] ?? 'General';

            if (in_array($moduleName, ['mapa', 'areas'])) {
                return 'Mapas';
            }

            if (in_array($moduleName, ['limites', 'departamento', 'provincia', 'municipio'])) {
                return 'Límites';
            }

            if (in_array($moduleName, ['panel', 'ayuda'])) {
                return 'Paneles';
            }

            if (in_array($moduleName, ['comentarios'])) {
                return 'Comentarios';
            }

            if (in_array($moduleName, ['media'])) {
                return 'Media Comunidad';
            }

            if (in_array($moduleName, ['reportes'])) {
                return 'Reportes Ambientales';
            }

            return ucfirst($moduleName);
        });
    }
}
