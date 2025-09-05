<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Insert default roles
        \DB::table('roles')->insert([
            ['name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Registrar', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cashier', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Student', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Create default accounts
        User::insert([
            [ 'name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('admin123'), 'role_id' => 1],
            [ 'name' => 'Registrar', 'email' => 'registrar@example.com', 'password' => bcrypt('registrar123'), 'role_id' => 2],
            [ 'name' => 'Cashier', 'email' => 'cashier@example.com', 'password' => bcrypt('cashier123'), 'role_id' => 3],
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
