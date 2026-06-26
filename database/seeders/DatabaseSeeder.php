<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gudang.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'admin', // Pastikan kolom role ada di tabel users Anda
            ]
        );

        echo "Akun Admin Berhasil Dibuat:\n";
        echo "Email: admin@gudang.com\n";
        echo "Pass : admin123\n";
    }
}
