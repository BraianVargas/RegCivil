<?php

use Illuminate\Database\Seeder;

class TypeProceedingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types_proceedings')->delete();

        DB::table('types_proceedings')->insert([
            [
                'id' => 1,
                'name' => 'Defunción',
            ],
            [
                'id' => 2,
                'name' => 'Matrimonio',
            ],
            [
                'id' => 3,
                'name' => 'Nacimiento',
            ],
            [
                'id' => 4,
                'name' => 'Union Convivencial',
            ]
        ]);
    }
}
