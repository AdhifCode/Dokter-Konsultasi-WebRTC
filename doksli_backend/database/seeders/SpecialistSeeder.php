<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialist;

class SpecialistSeeder extends Seeder
{
    public function run()
    {
        $specialists = [
            ['name' => 'General Practitioner'],
            ['name' => 'Dermatologist'],
            ['name' => 'Pediatrician'],
            ['name' => 'Dentist'],
            ['name' => 'Ophthalmologist'],
            ['name' => 'Clinical Nutritionist'],
            ['name' => 'Psychiatrist'],
            ['name' => 'Otolaryngologist'],
            ['name' => 'Cardiologist'],
            ['name' => 'Surgeon'],
            ['name' => 'Neurologist'],
            ['name' => 'Plastic Surgeon'],
            ['name' => 'Physiotherapist'],
            ['name' => 'Obstetrician and Gynecologist'],
            ['name' => 'Orthopedic Surgeon'],
            ['name' => 'Radiologist'],
            ['name' => 'Internist'],
            ['name' => 'Infectious Disease Specialist'],
            ['name' => 'Urologist'],
            ['name' => 'Reproductive Health Specialist'],
        ];

        Specialist::insert($specialists);
    }
}
