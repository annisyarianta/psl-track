<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'nama' => 'Dilla Anggraeni, S.T., M.T.',
            'nopeg' => '995',
            'email' => 'dilla.anggraeni@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'manager',
            'unit' => null,
            'must_change_password' => true,
        ]);
    }
}
