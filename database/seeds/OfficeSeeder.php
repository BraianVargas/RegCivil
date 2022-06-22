<?php

use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('offices')->delete();

        DB::table('offices')->insert([
            [
                'id' => 1,
                'name' => 'Oficina 1',
                'address' => 'Galarza 2222'
            ],
            [
                'id' => 2,
                'name' => 'Oficina 2',
                'address' => '9 de julio 324'
            ],
            [
                'id' => 3,
                'name' => 'Oficina 3',
                'address' => 'Artusi 777'
            ],
            [
                'id' => 4,
                'name' => 'Oficina 4',
                'address' => 'San Juan 1223'
            ],
            [
                'id' => 5,
                'name' => 'Oficina 5',
                'address' => 'Peru 222'
            ]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
