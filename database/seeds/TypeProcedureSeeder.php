<?php

use Illuminate\Database\Seeder;

class TypeProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types_procedures')->delete();

        DB::table('types_procedures')->insert([
            [
                'id' => 1,
                'name' => 'Partidas',
            ],
            [
                'id' => 2,
                'name' => 'DNI/PAS',
            ],

        ]);
    }
}
