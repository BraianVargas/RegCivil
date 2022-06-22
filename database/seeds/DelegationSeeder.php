<?php

use Illuminate\Database\Seeder;

class DelegationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('delegations')->delete();

        DB::table('delegations')->insert([
            [
                'id' => 1,
                'name' => 'JACHAL',
                'code' => 'JAC',
                'address' => '9 de julio 324',
                'zone' => 1
            ],
            [
                'id' => 2,
                'name' => 'RODEO',
                'code' => 'ROD',
                'address' => '9 de julio 324',
                'zone' => 1
            ],
            [
                'id' => 3,
                'name' => 'SAN JUAN',
                'code' => 'SJ',
                'address' => '9 de julio 324',
                'zone' => 1
            ],
            [
                'id' => 4,
                'name' => 'CAUCETE',
                'code' => 'CAU',
                'address' => '9 de julio 324',
                'zone' => 1
            ],
            [
                'id' => 5,
                'name' => 'POCITO',
                'code' => 'POC',
                'address' => '9 de julio 324',
                'zone' => 1
            ],
            [
                'id' => 6,
                'name' => 'MEDIA AGUA',
                'code' => 'MA',
                'address' => '9 de julio 324',
                'zone' => 1
            ],
            [
                'id' => 7,
                'name' => 'RIVADAVIA',
                'code' => 'RIV',
                'address' => '9 de julio 324',
                'zone' => 1
            ],
            [
                'id' => 8,
                'name' => 'MAIPU',
                'code' => 'MAI',
                'address' => '9 de julio 324',
                'zone' => 1
            ]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
