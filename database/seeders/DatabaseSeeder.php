<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit-posts']);
        Permission::create(['name' => 'delete-posts']);
        Permission::create(['name' => 'view-posts']);
        Permission::create(['name' => 'create-posts']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'user']);
        $role3 = Role::create(['name' => 'Super-Admin']);

        $role1->givePermissionTo('edit-posts', 'delete-posts', 'create-posts', 'view-posts');
        $role2->givePermissionTo('view-posts');
        $role3->givePermissionTo('edit-posts', 'create-posts', 'view-posts', 'delete-posts');


// super admin
        $user3 = User::factory()->create([
            'name' => 'Super-Admin User',
            'email' => 'superadmin@gmail.com',
            'password' => 'password'
        ]);
        $user3->assignRole($role3);

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        // create demo users
        $admins = User::factory(10)->create([
            'password' => 'password'
        ]);

        foreach ($admins as $admin ) {
            $admin->assignRole($role1);
            Post::factory(3)->create(['user_id' => $admin->id]);
        }

        $users = User::factory(10)->create(['password' => 'password']);
        foreach ($users as $user){
            $user->assignRole($role2);
        }
        
    }
}
