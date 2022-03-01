<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Barang::create([
            'kode_barang' => 'B001',
            'nama_barang' => 'Susu',
        ]);

        Barang::create([
            'kode_barang' => 'B002',
            'nama_barang' => 'Susu Madu',
        ]);
    }
}
