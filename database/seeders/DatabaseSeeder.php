<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $role = Role::create(['name' => 'admin']);
        $role = Role::create(['name' => 'user']);

        User::create([
            'name' => 'Super User',
            'user_name' => 'super_user',
            'email' => 'super@test.com',
            'password' => Hash::make('secret123')
        ]);


        $user = User::find(1)->first();

        $user->assignRole('admin');
    }
}
