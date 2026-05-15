<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        
        // Create Regular Users
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = User::create([
                'name' => "Volunteer User $i",
                'email' => "volunteer$i@example.com",
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]);
        }
        
        // Create Sample Events
        $events = [
            [
                'title' => 'Beach Cleanup Drive',
                'description' => 'Join us for a beach cleanup to protect marine life and keep our beaches beautiful.',
                'event_date' => now()->addDays(7),
                'event_time' => '09:00:00',
                'location' => 'Santa Monica Beach, CA',
                'max_volunteers' => 50,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Food Bank Distribution',
                'description' => 'Help distribute food to families in need at our local food bank.',
                'event_date' => now()->addDays(10),
                'event_time' => '10:00:00',
                'location' => 'Community Food Bank, LA',
                'max_volunteers' => 30,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Tree Planting Event',
                'description' => 'Plant trees in the city park to improve air quality and create green spaces.',
                'event_date' => now()->addDays(14),
                'event_time' => '08:00:00',
                'location' => 'Central Park, NY',
                'max_volunteers' => 100,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Animal Shelter Help',
                'description' => 'Assist at the local animal shelter with walking dogs and cleaning facilities.',
                'event_date' => now()->addDays(5),
                'event_time' => '13:00:00',
                'location' => 'Happy Paws Shelter, Chicago',
                'max_volunteers' => 20,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Senior Center Visit',
                'description' => 'Spend time with seniors, play games, and brighten their day.',
                'event_date' => now()->addDays(12),
                'event_time' => '14:00:00',
                'location' => 'Golden Years Senior Center, Miami',
                'max_volunteers' => 25,
                'created_by' => $admin->id,
            ],
        ];
        
        foreach ($events as $eventData) {
            Event::create($eventData);
        }
        
        // Attach volunteers to events
        $allEvents = Event::all();
        foreach ($users as $user) {
            // Each user joins 2-3 random events
            $randomEvents = $allEvents->random(rand(2, 3));
            foreach ($randomEvents as $event) {
                $user->events()->attach($event->id, [
                    'joined_at' => now(),
                    'status' => 'approved'
                ]);
            }
        }
    }
}