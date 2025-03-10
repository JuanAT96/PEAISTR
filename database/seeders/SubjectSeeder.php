<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject; // Asegúrate de importar el modelo Subject
use Illuminate\Support\Facades\DB; // Importa la clase DB
use Illuminate\Support\Facades\Storage; // Importa la clase Storage

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/public/subjects.csv'); // Ruta al archivo CSV

        if (!file_exists($filePath)) {
            $this->command->error('El archivo subjects.csv no se encontró en storage/app/public.');
            return;
        }

        $file = fopen($filePath, 'r');
        fgetcsv($file); // Saltar la primera fila (encabezados)

        DB::beginTransaction(); // Iniciar una transacción para mejorar el rendimiento

        try {
            while (($row = fgetcsv($file)) !== false) {
                Subject::create([
                    'subject_code' => $row[0] ?? null,
                    'subject_name' => $row[1] ?? null,

                    'curricular_org_unit' => $row[2] ?? null,
                    'contact_teacher' => $row[3] ?? null,
                    'practical_teaching' => $row[4] ?? null,
                    'self_employment' => $row[5] ?? null,
                ]);
            }

            DB::commit(); // Confirmar la transacción
            fclose($file);
            $this->command->info('Datos de asignaturas importados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            fclose($file);
            $this->command->error('Error al importar datos de asignaturas: ' . $e->getMessage());
        }
    }
}
