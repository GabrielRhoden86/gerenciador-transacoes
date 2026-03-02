<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    User::factory()->create([
        'name' => 'Admin Sistema',
        'email' => 'admin@exemplo.com',
        'password' => bcrypt('admin123'),
        'role' => 'admin'
    ]);

    User::factory()->create([
        'name' => 'Usuário Comum',
        'email' => 'user@exemplo.com',
        'password' => bcrypt('user123'),
        'role' => 'user'
    ]);

    Client::factory(10)->create();
 }
}
