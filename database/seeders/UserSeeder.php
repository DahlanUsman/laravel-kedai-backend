<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat atau mendapatkan role 'admin'
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Membuat user baru
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Gantilah dengan password yang sesuai
        ]);

        // Menugaskan role ke user setelah user disimpan
        $user->assignRole($role);

        // Memastikan bahwa relasi roles dimuat dengan benar
        $user = User::with('roles')->find($user->id); // Mengambil user yang baru saja dibuat dengan relasi roles

        // Menambahkan pengecekan null untuk menghindari error
        if ($user && $user->roles && $user->roles->isNotEmpty()) {
            // Jika koleksi roles tidak kosong, periksa apakah role 'admin' ada
            $hasRole = $user->roles->contains('name', 'admin'); // Memeriksa apakah role 'admin' ada
            if ($hasRole) {
                echo "User has been successfully assigned the 'admin' role.\n";
            } else {
                echo "Failed to assign the 'admin' role to the user.\n";
            }
        } else {
            echo "Roles not found or user does not have any roles.\n";
        }
    }
}
