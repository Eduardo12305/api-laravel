<?php

namespace Database\Seeders;
use App\Models\Permisson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permisson::created([
            'permisson_type' => 'Admin',
            'name' => 'Gerente'
        ]);
    }
}
