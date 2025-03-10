<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $administrativo = User::create([
            'name' => 'administrativo',
            'email' => 'administrativo@istr.com',
            'password' => Hash::make('administrativo')
        ]);
        $administrativo->assignRole('administrativo');
        
        $docente = User::create([
            'name' => 'docente',
            'email' => 'docente@istr.com',
            'password' => Hash::make('docente')
        ]);
        $docente->assignRole('docente');
        
        $coordinador = User::create([
            'name' => 'coordinador',
            'email' => 'coordinador@istr.com',
            'password' => Hash::make('coordinador')
        ]);
        $coordinador->assignRole('coordinador');
        
        $vicerrectorado = User::create([
            'name' => 'vicerrectorado',
            'email' => 'vicerrectorado@istr.com',
            'password' => Hash::make('vicerrectorado')
        ]);
        $vicerrectorado->assignRole('vicerrectorado');
        
        $estudiante = User::create([
            'name' => 'estudiante',
            'email' => 'estudiante@istr.com',
            'password' => Hash::make('estudiante')
        ]);
        $estudiante->assignRole('estudiante');
    }
}
