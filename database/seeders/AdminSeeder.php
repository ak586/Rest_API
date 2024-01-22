<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(1)->create([
        //     'name'=>"Admin",
        //     'email'=>'admin@gmail.com',
        //     'password'=>'password'
        // ])->assignRole('admin', 'writer');
        $user = User::find(30);
        $user->assignRole("admin");
    }
}
