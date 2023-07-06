<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agencias')->insert([
            [
                'nombre'=>"Buen Sazon Oruro",
                'direccion'=>"Urbanizacion HUAJARA II",
                'telefono'=>"73847194",
                'email'=>"",
                'web'=>"buensazonoruro",
                'logo'=>"348565664_261697269693249_544105869248815807_n.jpg",
                'color'=>"#006c1e",
            ]
        ]);
    }
}
