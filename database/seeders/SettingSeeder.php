<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('class_name', config('kaskelas.class_name'));
        Setting::set('weekly_amount', config('kaskelas.weekly_amount'));
        Setting::set('cash_start_date', config('kaskelas.start_date'));
        Setting::set('treasurer_name', 'Bendahara Kelas');
        Setting::set('class_leader_name', 'Ketua Kelas');
        Setting::set('school_name', 'SMK Telkom Sidoarjo');
    }
}
