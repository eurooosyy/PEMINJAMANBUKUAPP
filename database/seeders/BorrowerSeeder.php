<?php

namespace Database\Seeders;

use App\Models\Borrower;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class BorrowerSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user Siswa
        $siswaRole = Role::where('name', 'Siswa')->first();

        if (!$siswaRole) {
            $this->command->error('Role Siswa tidak ditemukan!');
            return;
        }

        $siswaUsers = User::where('role_id', $siswaRole->id)->get();

        foreach ($siswaUsers as $user) {
            Borrower::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => 'NIS-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                    'class' => 'XII IPA 1',
                    'phone' => '08' . rand(1000000000, 9999999999)
                ]
            );
        }

        $this->command->info('✅ ' . $siswaUsers->count() . ' Borrower dibuat untuk Siswa!');
    }
}
