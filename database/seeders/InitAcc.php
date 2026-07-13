<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\users;
use App\Models\college;
use App\Models\semyr;
use Illuminate\Support\Facades\Hash;

class InitAcc extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        college::create([
            'college_name' => 'Admin',
            'abbreviation' => 'admin',
            'description' => '',
        ]);

        college::create([
            'college_name' => 'College of Communication and Information technology',
            'abbreviation' => 'CCIT',
            'description' => '',
        ]);
    users::create([
        'first_name' => 'Eron',
        'last_name' => 'Tobia',
        'middle_name' => 'P.',
        'employee_ID' => '22-03940',
        'college_id' => '1',
        'role' => '1',
        'email' => 'admin@local',
        'password' => Hash::make ('admin123'),

    ]);
    
    users::create([
        'first_name' => 'Ashley',
        'last_name' => 'Domenden',
        'middle_name' => 'S.',
        'employee_ID' => '22-2222',
        'college_id' => '2',
        'role' => '2',
        'email' => 'dean@local',
        'password' => Hash::make ('admin123'),

    ]);
    users::create([
        'first_name' => 'John Paul',
        'last_name' => 'Castillo',
        'middle_name' => 'M.',
        'employee_ID' => '33-3333',
        'college_id' => '2',
        'role' => '3',
        'email' => 'asstdean@local',
        'password' => Hash::make ('admin123'),

    ]);

    users::create([
        'first_name' => 'Kerby',
        'last_name' => 'Palamo',
        'middle_name' => 'M.',
        'employee_ID' => '44-4444',
        'college_id' => '2',
        'role' => '4',
        'email' => 'faculty@local',
        'password' => Hash::make ('admin123'),

    ]);
    users::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'middle_name' => 'M.',
        'employee_ID' => '55-5555',
        'college_id' => '2',
        'role' => '5',
        'email' => 'programhead@local',
        'password' => Hash::make ('admin123'),
    ]);

        semyr::create([
            'semester' => '1st Semester',
            'school_year' => '2025-2026',
        ]);
    }
}

