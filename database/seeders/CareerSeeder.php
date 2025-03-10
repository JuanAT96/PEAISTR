<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Career; // Asegúrate de importar el modelo Career

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $careers = [
            ['name' => 'ADMINISTRACION FINANCIERA', 'modality' => 'PRESENCIAL'],
            ['name' => 'ADMINISTRACION PARA ECONOMIA POPULAR Y SOLIDARIA MICROS Y PEQUEÑAS EMPRESAS', 'modality' => 'PRESENCIAL'],
            ['name' => 'ADMINISTRACION TURISTICA Y HOTELERA', 'modality' => 'PRESENCIAL'],
            ['name' => 'ASISTENCIA EN FARMACIA', 'modality' => 'PRESENCIAL'],
            ['name' => 'CENTRO DE IDIOMAS  GENERAL', 'modality' => 'PRESENCIAL'],
            ['name' => 'CENTRO DE IDIOMAS  INTENSIVO', 'modality' => 'PRESENCIAL'],
            ['name' => 'DESARROLLO DE SOFTWARE', 'modality' => 'PRESENCIAL'],
            ['name' => 'DOCENCIA MUSICAL', 'modality' => 'SEMI-PRESENCIAL'],
            ['name' => 'EMERGENCIAS MEDICAS', 'modality' => 'PRESENCIAL'],
            ['name' => 'ESTIMULACION TEMPRANA', 'modality' => 'PRESENCIAL'],
            ['name' => 'INFORMATICA Y MULTIMEDIA', 'modality' => 'PRESENCIAL'],
            ['name' => 'MEDIOS DIDACTICOS MUSICALES', 'modality' => 'SEMI-PRESENCIAL'],
            ['name' => 'MUSICA POPULAR', 'modality' => 'PRESENCIAL'],
            ['name' => 'PREPARATEC', 'modality' => 'PRESENCIAL'],
            ['name' => 'PREPARATEC V02', 'modality' => 'NIVELACIÓN'],
            ['name' => 'PREPARATEC-VIRTUAL', 'modality' => 'EN LÍNEA'],
            ['name' => 'PROCESAMIENTO DE ALIMENTOS', 'modality' => 'PRESENCIAL'],
            ['name' => 'REGENCIA EN FARMACIA', 'modality' => 'PRESENCIAL'],
            ['name' => 'REHABILITACION FISICA', 'modality' => 'PRESENCIAL'],
            ['name' => 'SERVICIOS ASISTENCIALES DE SALUD', 'modality' => 'PRESENCIAL'],
            ['name' => 'TECNICO SUPERIOR EN SEGURIDAD CIUDADANA Y ORDEN PUBLICO', 'modality' => 'PRESENCIAL'],
            ['name' => 'TECNOLOGIA SUPERIOR EN ADMINISTRACION FINANCIERA', 'modality' => 'PRESENCIAL'],
            ['name' => 'TECNOLOGIA SUPERIOR EN CONSTRUCCION', 'modality' => 'DUAL'],
            ['name' => 'TECNOLOGIA SUPERIOR EN DESARROLLO DE SOFTWARE', 'modality' => 'PRESENCIAL'],
            ['name' => 'TECNOLOGIA SUPERIOR EN GESTION DE OPERACIONES TURISTICAS', 'modality' => 'PRESENCIAL'],
            ['name' => 'TURISMO', 'modality' => 'PRESENCIAL'],
        ];

        foreach ($careers as $career) {
            Career::create($career);
        }
    }
}
