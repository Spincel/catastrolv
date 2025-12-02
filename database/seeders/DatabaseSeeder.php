<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Usuario ADMINISTRADOR
        User::create([
            'name' => 'Administrador Catastro',
            'email' => 'admin@catastro.com',
            'password' => Hash::make('admin123'), // Contraseña para entrar
            'role' => 'admin',
        ]);

        // 2. Usuario NORMAL (Operador)
        User::create([
            'name' => 'Operador Ventanilla',
            'email' => 'operador@catastro.com',
            'password' => Hash::make('operador123'), // Contraseña para entrar
            'role' => 'user',
        ]);

        echo "Usuarios creados correctamente:\n";
        echo "1. Admin: admin@catastro.com | pass: admin123\n";
        echo "2. Operador: operador@catastro.com | pass: operador123\n";
    }
}