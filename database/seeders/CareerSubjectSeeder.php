<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CareerSubject;
use App\Models\Career;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class CareerSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/public/career_subjects.csv');

        if (!file_exists($filePath)) {
            $this->command->error('El archivo career_subjects.csv no se encontró en storage/app/public.');
            return;
        }

        $file = fopen($filePath, 'r');
        fgetcsv($file); // Saltar la primera fila (encabezados)

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($file)) !== false) {
                $careerName = $row[0] ?? null;
                $level = $row[1] ?? null;
                $subjectCode = $row[2] ?? null;

                $career = Career::where('name', $careerName)->first();
                $subject = Subject::where('subject_code', $subjectCode)->first();

                if ($career && $subject) {
                    CareerSubject::create([
                        'career_id' => $career->id,
                        'subject_id' => $subject->id,
                        'level' => $level,
                    ]);
                } else {
                    $this->command->warn("No se encontró carrera o asignatura para: Carrera: {$careerName}, Asignatura: {$subjectCode}");
                }
            }

            DB::commit();
            fclose($file);
            $this->command->info('Datos de career_subjects importados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($file);
            $this->command->error('Error al importar datos de career_subjects: ' . $e->getMessage());
        }
    }
}
