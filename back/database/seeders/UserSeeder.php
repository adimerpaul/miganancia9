<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'=> 'Administador',
                'nickname'=> 'Admin',
                'password'=> bcrypt('admin123A'),
                'type'=> 'admin',
                'agencia_id'=> 1
            ],
            [
                'name'=> 'Amilkar Daniel Quispaya Lima',
                'nickname'=> 'Buenadmin',
                'password'=> bcrypt('Buenadmin'),
                'type'=> 'admin',
                'agencia_id'=> 1
            ],
            [
                'name'=> 'Vendedor',
                'nickname'=> 'Buenvendedor',
                'password'=> bcrypt('buen123'),
                'type'=> 'user',
                'agencia_id'=> 1
            ],
        ]);
    }
}
