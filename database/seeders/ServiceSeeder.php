<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create services for the past 3 months
        $startDate = now()->subMonths(3)->startOfWeek();
        $endDate = now();
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            // Sunday Service
            Service::create([
                'name' => 'Sunday Service',
                'service_type' => 'regular',
                'service_date' => $currentDate->format('Y-m-d') . ' 09:00:00',
                'expected_attendance' => 100,
                'notes' => 'Regular Sunday Service',
                'is_recurring' => true,
            ]);

            // Wednesday Bible Study
            Service::create([
                'name' => 'Bible Study',
                'service_type' => 'bible_study',
                'service_date' => $currentDate->addDays(3)->format('Y-m-d') . ' 18:30:00',
                'expected_attendance' => 50,
                'notes' => 'Weekly Bible Study',
                'is_recurring' => true,
            ]);

            // Move to next week
            $currentDate->addDays(4)->startOfWeek();
        }

        // Add some special services
        Service::create([
            'name' => 'Easter Service',
            'service_type' => 'special',
            'service_date' => now()->addDays(10)->format('Y-m-d') . ' 10:00:00',
            'expected_attendance' => 200,
            'notes' => 'Special Easter Celebration',
            'is_recurring' => false,
        ]);

        Service::create([
            'name' => 'Youth Conference',
            'service_type' => 'conference',
            'service_date' => now()->addDays(15)->format('Y-m-d') . ' 14:00:00',
            'expected_attendance' => 150,
            'notes' => 'Annual Youth Conference',
            'is_recurring' => false,
        ]);
    }
} 