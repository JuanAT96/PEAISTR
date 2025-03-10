<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->paginate(10);
        return Inertia::render('Roles/Index', [
            'roles' => $roles->items(),
            'pagination' => $roles->toArray(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Roles/Create', [
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);
            //dd($role);
            try {
                $role->syncPermissions($validated['permissions']);
            } catch (\Exception $syncException) {
                // Captura la excepción específica de syncPermissions
                DB::rollback();
                //dd('error', 'Error al sincronizar permisos: ' . $syncException->getMessage());

                return back()->dangerBanner('Error al sincronizar permisos: ' . $syncException->getMessage());
            }

            DB::commit();
            // dd('success', 'Rol creado exitosamente');
            return redirect()->route('roles.index')->banner('Rol creado exitosamente');
        } catch (\Exception $e) {
            DB::rollback();
            // dd('error', 'Error al crear el rol: ' . $e->getMessage());
            return back()->dangerBanner('Error al crear el rol: ' . $e->getMessage());
        }
    }

    public function edit(Role $role)
    {
        return Inertia::render('Roles/Edit', [
            'role' => $role->load('permissions'),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $validated['name'],
            ]);
            $role->syncPermissions($validated['permissions']);
            DB::commit();
            return redirect()->route('roles.index')->banner('Rol actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->dangerBanner('Error al actualizar el rol');
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return redirect()->route('roles.index')->banner('Rol Eliminado con éxito!');
        } catch (\Exception $e) {
            return back()->dangerBanner('Error al eliminar el rol');
        }
    }
}
