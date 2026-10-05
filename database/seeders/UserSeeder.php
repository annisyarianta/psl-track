<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Dilla Anggraeni, S.T., M.T.',
            'nopeg' => '995',
            'email' => 'dilla.anggraeni@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'manager',
            'id_unit' => null,
            'must_change_password' => true,
        ]);
    }
}