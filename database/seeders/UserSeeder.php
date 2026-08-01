<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * NOTE: these are TEMPORARY passwords, documented here on purpose so the
     * first login is possible. Every seeded account has must_change_password = true,
     * which forces a password change immediately after the first login.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'bendahara@kaskelas.test'],
            [
                'name' => 'Bendahara Kelas',
                'password' => 'Bendahara#2026',
                'role' => User::ROLE_TREASURER,
                'must_change_password' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'ketua@kaskelas.test'],
            [
                'name' => 'Ketua Kelas',
                'password' => 'KetuaKelas#2026',
                'role' => User::ROLE_CLASS_LEADER,
                'must_change_password' => true,
            ]
        );
    }
}
