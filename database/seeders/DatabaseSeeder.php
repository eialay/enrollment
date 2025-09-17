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
            [ 'name' => 'Sample Admin', 'email' => 'admin@example.com', 'password' => bcrypt('admin123'), 'role_id' => 1],
            [ 'name' => 'Sample Registrar', 'email' => 'registrar@example.com', 'password' => bcrypt('registrar123'), 'role_id' => 2],
            [ 'name' => 'Sample Cashier', 'email' => 'cashier@example.com', 'password' => bcrypt('cashier123'), 'role_id' => 3],
            [ 'name' => 'Juan Dela Cruz', 'email' => 'student@example.com', 'password' => bcrypt('1234567'), 'role_id' => 4],
        ]);

        \DB::table('students')->insert([
            [
                'user_id' => 4,
                'firstname' => 'Juan',
                'middlename' => 'Santos',
                'lastname' => 'Dela Cruz',
                'contact' => '09171234567',
                'gender' => 'Male',
                'birthdate' => '2005-05-15',
                'address' => '123 Main St, Cityville',
                'studentImage' => '',
                'birthCertificate' => 'documents/Birth-Certificate.pdf',
                'guardianFName' => 'Pedro',
                'guardianMName' => 'Lopez',
                'guardianLName' => 'Dela Cruz',
                'guardianEmail' => 'pedro.lopez@example.com',
                'guardianContact' => '09181234567',
                'guardianRelationship' => 'Father',
                'guardianAddress' => '123 Main St, Cityville',
            ],
        ]);

        \DB::table('enrollments')->insert([
            [
                'student_id' => 1,
                'reference_code' => 'ENR24' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'status' => 'Pending Review',
                'grade_level' => '10',
                'school_year' => '2024-2025',
                'course' => 'BSIT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
