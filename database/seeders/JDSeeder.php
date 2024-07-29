<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class JDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view-jd-tasks',
            'create-jd-tasks',
            'update-jd-tasks',
            'delete-jd-tasks',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $permissions = Permission::orderBy('id')->pluck('id', 'id')->all();

        $role = Role::find(1);
        $role->syncPermissions($permissions);

        $user = User::find(1);
        $user->assignRole([$role->id]);
    }
}
