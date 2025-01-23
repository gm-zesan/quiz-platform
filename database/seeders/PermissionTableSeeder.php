<?php



namespace Database\Seeders;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;



class PermissionTableSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $permissions = [
            // role
            ['name' => 'role-list', 'display_name' => 'Role list', 'module' => 'role'],
            ['name' => 'role-create', 'display_name' => 'Role create', 'module' => 'role'],
            ['name' => 'role-edit', 'display_name' => 'Role edit', 'module' => 'role'],
            ['name' => 'role-delete', 'display_name' => 'Role delete', 'module' => 'role'],

            // user
            ['name' => 'user-list', 'display_name' => 'User list', 'module' => 'user'],
            ['name' => 'user-create', 'display_name' => 'User create', 'module' => 'user'],
            ['name' => 'user-edit', 'display_name' => 'User edit', 'module' => 'user'],
            ['name' => 'user-delete', 'display_name' => 'User delete', 'module' => 'user'],

            // quiz
            ['name' => 'quiz-list', 'display_name' => 'Quiz list', 'module' => 'quiz'],
            ['name' => 'quiz-create', 'display_name' => 'Quiz create', 'module' => 'quiz'],
            ['name' => 'quiz-edit', 'display_name' => 'Quiz edit', 'module' => 'quiz'],
            ['name' => 'quiz-delete', 'display_name' => 'Quiz delete', 'module' => 'quiz'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }

}
