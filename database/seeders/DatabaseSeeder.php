<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            MenuSeeder::class,
            JenjangKelasSeeder::class,
            TahunAjaranSeeder::class,
            KelasSeeder::class,
            AdminUserSeeder::class,
            SiswaDummySeeder::class,
        ]);
    }
}
