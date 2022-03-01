<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'username' => 'admin',
            'password' => Hash::make(md5('admin')),
            'role' => 1
        ]);

        User::create([
            'username' => 'staff',
            'password' => Hash::make(md5('staff')),
            'role' => 2
        ]);
    }
}
