<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        
        //Crear usuario de prueba cada vez que se ejecuten las migraciones
        User::factory()->create([
            'name'     => 'Tester',
            'email'    => 'test@example.com',
            'password' => bcrypt('12345678'),
            'id_number' => '123456789',
            'phone' => '9999999999',
            'address' => 'Test address',
        ])->assignRole('Administrador');
    
    }
}