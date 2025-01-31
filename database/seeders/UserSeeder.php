<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Zesan',
            'email' => 'gmzesan7767@gmail.com',
            'password' => bcrypt('12345678aA'),
        ]);
        $user = User::create([
            'name' => 'Hasan',
            'email' => 'hasan@gmail.com',
            'password' => bcrypt('12345678aA'),
        ]);

        $permissions = Permission::pluck('id','name')->all();
        $admin->assignRole('admin');
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);

        $user->assignRole('user');
    }
}
