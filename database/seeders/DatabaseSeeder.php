<?php

namespace Database\Seeders;

use App\Models\User;
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
        $administrator = User::factory()->create([
            'name' => 'مدير سرد',
            'email' => 'admin@sard.test',
        ]);

        $administrator->forceFill(['is_admin' => true])->save();

        $this->call(QuoteSeeder::class);
    }
}
