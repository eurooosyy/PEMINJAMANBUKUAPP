<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if not exists
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $rolePetugas = Role::firstOrCreate(['name' => 'Petugas']);
        $roleSiswa = Role::firstOrCreate(['name' => 'Siswa']);

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role_id' => $roleAdmin->id
            ]
        );

        // Create petugas user
        User::firstOrCreate(
            ['email' => 'petugas@mail.com'],
            [
                'name' => 'Petugas',
                'password' => Hash::make('petugas123'),
                'role_id' => $rolePetugas->id
            ]
        );

        // Create student users
        $studentEmails = [
            'siswa1@mail.com',
            'siswa2@mail.com',
            'siswa3@mail.com',
        ];

        foreach ($studentEmails as $index => $email) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Siswa ' . ($index + 1),
                    'password' => Hash::make('siswa123'),
                    'role_id' => $roleSiswa->id
                ]
            );
        }

        $this->command->info('✅ Users dan Roles sudah disiapkan!');
    }
}
