<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $permission=Permission::create(["name"=> "view articles"]);
       $role= Role::create(['name'=>'user']);
        $users = User::factory(2)->create();
        $role->givePermissionTo($permission);
        $users[0]->assignRole($role);
        $users[1]->assignRole($role);
    }
}
