<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage user']);
        Permission::create(['name' => 'manage customer']);
        Permission::create(['name' => 'manage product']);
        Permission::create(['name' => 'manage order']);
        Permission::create(['name' => 'create order']);

        Role::create(['name' => 'super-admin'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'admin'])->givePermissionTo(['manage user', 'manage product']);
        Role::create(['name' => 'staff'])->givePermissionTo(['manage customer', 'manage product', 'manage order']);
        Role::create(['name' => 'customer'])->givePermissionTo(['create order']);

        User::create([
            'name' => 'Super Admin',
            'email' => 'super.admin@laravel.com',
            'password' => Hash::make('superadmin1234'),
        ])->assignRole('super-admin');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@laravel.com',
            'password' => Hash::make('admin1234'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Staff',
            'email' => 'staff@laravel.com',
            'password' => Hash::make('staff1234'),
        ])->assignRole('staff');

        User::create([
            'name' => 'Customer',
            'email' => 'customer@laravel.com',
            'password' => Hash::make('customer1234'),
        ])->assignRole('customer');
    }
}
