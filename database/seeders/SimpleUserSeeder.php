<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SimpleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'      => 'Usman',
            'email'     => 'usman@gmail.com',
            'password'  => Hash::make('12345678'),
            'created_by'  => 1,
        ]);

        // $role = Role::create(['name' => 'Super Admin']);
        // $permissions = Permission::pluck('id','id')->all();
        
        // $role->syncPermissions($permissions);
        $user->assignRole([2]);
    }
}
