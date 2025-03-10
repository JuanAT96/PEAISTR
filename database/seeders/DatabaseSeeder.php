<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Career;
use App\Models\Subject;
use App\Models\CareerSubject;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RoleSeeder::class, UserSeeder::class, CareerSeeder::class, SubjectSeeder::class, CareerSubjectSeeder::class]);
    }
}
