<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $role = Role::create(['name' => 'admin']);
        $roleguest = Role::create(['name' => 'guest']);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
        ]);

        $user->assignRole($role);
    }
}
