<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\CareerRequest;
use Illuminate\Support\Facades\Storage;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* $careers = Career::all();
    return Inertia::render('Careers/Index', [
        'careers' => $careers
    ]);*/
        define('numero_elementos_por_pagina', 26);
        $careers = Career::orderBy('id', 'desc')->paginate(numero_elementos_por_pagina);
        return inertia('Careers/Index', [
            'careers' => $careers->items(),
            'pagination' => [
                'total' => $careers->total(),
                'per_page' => $careers->perPage(),
                'current_page' => $careers->currentPage(),
                'last_page' => $careers->lastPage(),
                'from' => $careers->firstItem(),
                'to' => $careers->lastItem(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Careers/Create');
    }

    /**
     * Store a newly created resource in storage.
     * @param App\Http\Request\CareerRequest
     * @return App\Http\Request\Response
     */
    public function store(CareerRequest $request)
    {
        // dd($request->all());
        //Career::create($request->validated());
        //return redirect()->route('careers.index');
        $validatedData = $request->validated();

        if ($request->hasFile('logo_path')) {
            $path = $request->file('logo_path')->store('careers', 'public');
            $validatedData['logo_path'] = $path;
        }

        Career::create($validatedData);
        $request->session()->flash('flash.banner', 'Carrera creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('careers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Career $career)
    {
        return inertia('Careers/Edit', ['career' => $career]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CareerRequest $request, Career $career)
    {
        // Depuración: muestra todos los datos recibidos
        // dd($request->all());

        $validatedData = $request->validated();

        if ($request->hasFile('logo_path')) {
            // Elimina el logo anterior si existe
            if ($career->logo_path) {
                Storage::disk('public')->delete($career->logo_path);
            }
            $path = $request->file('logo_path')->store('careers', 'public');
            $validatedData['logo_path'] = $path;
        } elseif ($request->has('existing_logo_path')) {
            $validatedData['logo_path'] = $request->input('existing_logo_path');
        }

        // Actualiza la carrera con los datos validados
        $career->update($validatedData);
        $request->session()->flash('flash.banner', 'Carrera actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        // Redirige a la lista de carreras
        return redirect()->route('careers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Career $career)
    {
        if ($career->logo_path) {
            // Elimina el logo si existe
            Storage::disk('public')->delete($career->logo_path);
        }

        $career->delete();

        return redirect()->route('careers.index')->banner('Carrera Eliminada con éxito!');
    }
}
