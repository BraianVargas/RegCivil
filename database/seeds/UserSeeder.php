<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('users')->delete();

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
            ],
            [
                'id' => 2,
                'name' => 'Telefonista',
                'email' => 'telefonista@telefonista.com',
                'password' => Hash::make('telefonista'),
            ]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
