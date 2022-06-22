<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(TypeDocumentSeeder::class);
        $this->call(TypeProcedureSeeder::class);
        $this->call(TypeProceedingSeeder::class);
        $this->call(DelegationSeeder::class);
        $this->call(PersonSeeder::class);
        $this->call(OfficeSeeder::class);
    }
}
