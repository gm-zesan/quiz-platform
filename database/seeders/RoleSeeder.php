<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = array(
            array('id' => '1','name' => 'admin', 'description' => 'All permission and access are enabled for this role', 'guard_name' => 'web'),
            array('id' => '2','name' => 'user', 'description' => 'User can observe everything without role', 'guard_name' => 'web'),
        );
        foreach($datas as $data)
        {
            Role::create($data);
        }
    }
}
