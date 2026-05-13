<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScholarGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scholargrades = [
            '1° de primaria',
            '2° de primaria',
            '3° de primaria',
            '4° de primaria',
            '5° de primaria',
            '6° de primaria',
            '1° de secundaria',
            '2° de secundaria',
            '3° de secundaria',
            '1° de preparatoria',
            '2° de preparatoria',
            '3° de preparatoria'
        ];

        foreach ($scholargrades as $grade) {
            \App\Models\ScholarGrade::firstOrCreate([
                'name' => $grade,
            ]);
        }
    }
}