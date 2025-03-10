<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Inertia\Response;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display  a listing of the resource.
     */
    public function index()
    {
        /*
        $users = User::all();
        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);*/
        define('numero_elementos_por_pagina', 10);
        //$users = User::All();

        $users = User::with('roles')
            ->orderBy('name', 'asc')
            ->paginate(numero_elementos_por_pagina);
        return inertia('Users/Index', [
            'users' => $users->items(),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return inertia('Users/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param App\Http\Request\UserRequest
     * @return App\Http\Request\Response
     */
    public function store(UserRequest $request)
    {
        //User::create($request->validated());
        //return redirect()->route('Users.index');
        // Validar los datos del formulario, incluyendo el rol
        $validated = $request->validated();

        // Crear el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Asignar el rol al usuario
        $user->assignRole($validated['role']);
        $request->session()->flash('flash.banner', 'Usuario creado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        // Redirigir o devolver una respuesta
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $roles = Role::all();
        return inertia('Users/Assign', [
            'users' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return inertia('Users/Edit', [
            'users' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        // $user->update($request->validated());
        // return redirect()->route('Users.index');

        // Validar los datos del formulario, incluyendo el rol
        $validated = $request->validated();

        // Actualizar los datos del usuario
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
        ]);

        // Sincronizar el rol del usuario
        $user->syncRoles($validated['role']);
        $request->session()->flash('flash.banner', 'Usuario actualizado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        // Redirigir o devolver una respuesta
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->banner('Rol del usuario Eliminado con éxito!');
    }
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string',
        ]);

        $user->syncRoles([$validated['role']]);
        return redirect()->route('users.index')->banner('Rol del usuario actualizado con éxito.');

        //return redirect()->route('users.index')->with('success', 'Rol del usuario actualizado con éxito.');
    }
}
