<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'user 1',
            'email' => 'test1@example.com',
            'type' => 'donator',
            'password' => Hash::make('123')
        ]);

        User::create([
            'name' => 'user 2',
            'email' => 'test2@example.com',
            'type' => 'donator',
            'password' => Hash::make('123')
        ]);

        User::create([
            'name' => 'user 3',
            'email' => 'test3@example.com',
            'type' => 'donator',
            'password' => Hash::make('123')
        ]);

        User::create([
            'name' => 'admin 1',
            'email' => 'admin@example.com',
            'type' => 'admin',
            'password' => Hash::make('123')
        ]);

        Event::create([
            'title' => 'event 1',
            'description' => 'description 1',
            'date' => (new \DateTime('2025-11-24 10:00:00'))->format('Y-m-d H:i:s'),
            'image' => asset("images/example.jpeg"),
            'donationTotal' => 10000
        ]);

        Event::create([
            'title' => 'event 2',
            'description' => 'description 2',
            'date' => (new \DateTime('2025-11-24 10:00:00'))->format('Y-m-d H:i:s'),
            'image' => asset("images/example.jpeg"),
            'donationTotal' => 20000
        ]);

        Event::create([
            'title' => 'event 3',
            'description' => 'description 3',
            'date' => (new \DateTime('2025-11-24 10:00:00'))->format('Y-m-d H:i:s'),
            'image' => asset("images/example.jpeg"),
            'donationTotal' => 50000
        ]);

        Donation::create([
            'event_id' => 1,
            'user_id' => 1,
            'amount' => 10000,
            'date' => now()
        ]);

        Donation::create([
            'event_id' => 2,
            'user_id' => 1,
            'amount' => 20000,
            'date' => now()
        ]);
    }
}
