<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $expense = [
            'Perlengkapan Kelas',
            'ATK',
            'Dekorasi',
            'Akademik',
            'Kegiatan Kelas',
            'Konsumsi',
            'Lainnya',
        ];

        $income = [
            'Kas Mingguan',
            'Donasi',
            'Lainnya',
        ];

        foreach ($expense as $name) {
            Category::firstOrCreate(['name' => $name, 'type' => 'expense']);
        }

        foreach ($income as $name) {
            Category::firstOrCreate(['name' => $name, 'type' => 'income']);
        }
    }
}
