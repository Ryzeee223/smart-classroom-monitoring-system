<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class main extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create ([
'fist_name' => 'admin',
'last_name' => 'admin',
'email' => 'admin@local.com',
'password' => bcrypt('admin123'),
'role' => '1',
        ]);
    }
}
