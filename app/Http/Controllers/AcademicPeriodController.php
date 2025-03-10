<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\AcademicPeriodRequest;
use Illuminate\Http\RedirectResponse;

class AcademicPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);
        $academicperiods = AcademicPeriod::orderBy('name', 'asc')->paginate(NUMERO_ELEMENTOS_POR_PAGINA);
        return inertia('AcademicPeriods/Index', [
            'academicPeriods' => $academicperiods->items(),
            'pagination' => [
                'total' => $academicperiods->total(),
                'per_page' => $academicperiods->perPage(),
                'current_page' => $academicperiods->currentPage(),
                'last_page' => $academicperiods->lastPage(),
                'from' => $academicperiods->firstItem(),
                'to' => $academicperiods->lastItem(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('AcademicPeriods/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AcademicPeriodRequest $request): RedirectResponse
    {
        AcademicPeriod::create($request->validated());
        $request->session()->flash('flash.banner', 'Período académico creado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        //dd(session()->all()); // Verifica la sesión
        // return redirect()->route('academicperiods.index')->with('message', 'Período académico creado con éxito!');
        return redirect()->route('academicperiods.index');
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
    public function edit(AcademicPeriod $academicperiod)
    {
        return inertia('AcademicPeriods/Edit', ['academicPeriod' => $academicperiod]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AcademicPeriodRequest $request, AcademicPeriod $academicperiod)
    {
        //dd($request->all());
        $academicperiod->update($request->validated());
        $request->session()->flash('flash.banner', 'Período académico actualizado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('academicperiods.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicPeriod $academicperiod)
    {
        $academicperiod->delete();
        return redirect()->route('academicperiods.index')->banner('Período académico Eliminado con éxito!');
    }
}
