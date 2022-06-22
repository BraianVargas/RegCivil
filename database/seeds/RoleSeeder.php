<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('roles')->delete();

        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'Administrador',
                'slug' => 'administrador'
            ],
            [
                'id' => 2,
                'name' => 'Telefonista',
                'slug' => 'telefonista'
            ]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
