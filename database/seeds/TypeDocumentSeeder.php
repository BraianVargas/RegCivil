<?php

use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('types_documents')->delete();

        DB::table('types_documents')->insert([
            [
                'id' => 1,
                'name' => 'DNI',
            ],
            [
                'id' => 2,
                'name' => 'PAS',
            ],
            [
                'id' => 3,
                'name' => 'LC',
            ]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
