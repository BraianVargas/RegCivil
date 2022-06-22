<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('permissions')->delete();

        DB::table('permissions')->insert([
            [
                'id' => 1,
                'name' => 'Gestion de Usuarios',
                'slug' => 'usuario-administrar'
            ],
            [
                'id' => 2,
                'name' => 'Gestión de Turnos',
                'slug' => 'turno-administrar'
            ],
            [
                'id' => 3,
                'name' => 'Registrar Turnos',
                'slug' => 'turno-registrar'
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
