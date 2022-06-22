<?php

use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('persons')->delete();

        DB::table('persons')->insert([
            [
                'id' => 1,
                'first_name' => 'Juan',
                'last_name' => 'Perez',
                'birth_date' => '1977-05-25',
                'address' => 'Cochabamba 568',
                'location' => 'Concepcion del Uruguay',
                'postal_code' => '3260',
                'phone' => '12345678',
                'cell_phone' => '87654321',
                'another_phone' => '3333333',
                'email' => 'udrizardm@gmail.com',
                'gender' => 1,
                'document_number' => '25902132',
                'document_type_id' => 1,
            ],
            [
                'id' => 2,
                'first_name' => 'Miriam',
                'last_name' => 'Jumilla',
                'birth_date' => '1980-05-25',
                'address' => 'Cochabamba 568',
                'location' => 'Concepcion del Uruguay',
                'postal_code' => '3260',
                'phone' => '12345678',
                'cell_phone' => '87654321',
                'another_phone' => '3333333',
                'email' => 'udrizardm@gmail.com',
                'gender' => 1,
                'document_number' => '24888777',
                'document_type_id' => 1,
            ]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
