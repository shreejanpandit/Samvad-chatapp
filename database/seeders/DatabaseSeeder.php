<?php

namespace Database\Seeders;

use App\Models\Ticket;
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
        // $user =  User::factory(10)->create();

        // Ticket::factory(100)->recycle($user)->create();
        User::factory()->create([
            'name' => 'admin User',
            'email' => 'admin@admin.com',
        ]);
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
        ]);
        User::factory()->create([
            'name' => 'Test',
            'email' => 'test@test.com',
        ]);
    }
}
