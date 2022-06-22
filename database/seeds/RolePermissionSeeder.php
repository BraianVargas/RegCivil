<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Permission;
use App\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles_permissions')->delete();
        // DB::table('users_permissions')->delete();
        DB::table('users_roles')->delete();

        DB::table('roles_permissions')->insert([
            [
                'role_id' => 1,
                'permission_id' => 1
            ],
            [
                'role_id' => 1,
                'permission_id' => 2
            ],
            [
                'role_id' => 1,
                'permission_id' => 3
            ],
            [
                'role_id' => 2,
                'permission_id' => 3
            ]
        ]);

        DB::table('users_roles')->insert([
            [
                'role_id' => 1,
                'user_id' => 1
            ],
            [
                'role_id' => 2,
                'user_id' => 2
            ]
        ]);
    }
}
