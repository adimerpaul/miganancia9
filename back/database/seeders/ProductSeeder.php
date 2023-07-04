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
            ],
            [
                'name'=>"K'JARAS",
                'image'=>'336540764_961502541528726_5816604272043857324_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
            ],
            [
                'name'=>'CHICHARON',
                'image'=>'335622101_522263930055669_9088479432770288410_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
            ],
            [
                'name'=>'PLANCHITAS INDIVIDUALES',
                'image'=>'335498577_610677840912553_2047729733895289648_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
            ],
            [
                'name'=>'PLANCHITAS FAMILIARES',
                'image'=>'336462536_539890774920134_231612580536053265_n.jpg',
                'price'=>50,
                'cost'=>50,
                'amount'=>100,
                'agencia_id'=>1,
                'user_id'=>2,
            ],
        ]);
    }
}
