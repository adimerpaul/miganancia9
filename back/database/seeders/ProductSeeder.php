<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name'=>'Pique',
                'image'=>'335448575_949037916094248_7992765055281601092_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
                'description'=>'Consiste en trozos de carne de vaca y patatas fritas. También se le añade cebolla, locoto, huevos duros, queso cortado, mostaza, mayonesa y kétchup.',
            ],
            [
                'name'=>"K'jaras",
                'image'=>'336540764_961502541528726_5816604272043857324_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
                'description'=>'Es un plato típico de la gastronomía boliviana, que consiste en una sopa de carne de res, con verduras y fideos.',
            ],
            [
                'name'=>'Chicharron',
                'image'=>'335622101_522263930055669_9088479432770288410_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
                'description'=>'También conocido como Chicharrón de chancho en Cochabamba, es el plato estrella de este departamento porque comenzó a prepararlo de diferentes modos hasta lograr encontrar el mejor punto.',
            ],
            [
                'name'=>'Planchitas individuales',
                'image'=>'335498577_610677840912553_2047729733895289648_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
                'description'=>'Consiste en trozos de carne de vaca y patatas fritas. También se le añade cebolla, locoto, huevos duros, queso cortado, mostaza, mayonesa y kétchup.',
            ],
            [
                'name'=>'Planchitas Familiares',
                'image'=>'336462536_539890774920134_231612580536053265_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
                'description'=>'Consiste en trozos de carne de vaca y patatas fritas. También se le añade cebolla, locoto, huevos duros, queso cortado, mostaza, mayonesa y kétchup.',
            ],
        ]);
    }
}
