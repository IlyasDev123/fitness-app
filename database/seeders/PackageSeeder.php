<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages =  [
            [
                'name' => 'Daily',
                'inapp_package_id' => 'daily',
                'price' => 0.99,
                'duration' => 1,
                'description' => json_encode([
                    'An The full context of your organization’s message history at your fingertips.',
                    'An Timely info and actions in one place with unlimited integrations pair.',
                    'Face-to-face communication',
                    'Secure collaboration with outside organizans or guests from within Slack'
                ]),
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Weekly',
                'inapp_package_id' => 'weekly',
                'price' => 4.99,
                'duration' => 2,
                'description' => json_encode([
                    'An The full context of your organization’s message history at your fingertips.',
                    'An Timely info and actions in one place with unlimited integrations pair.',
                    'Face-to-face communication',
                    'Secure collaboration with outside organizans or guests from within Slack'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monthly',
                'inapp_package_id' => 'monthly',
                'price' => 9.99,
                'duration' => 3,
                'description' => json_encode([
                    'An The full context of your organization’s message history at your fingertips.',
                    'An Timely info and actions in one place with unlimited integrations pair.',
                    'Face-to-face communication',
                    'Secure collaboration with outside organizans or guests from within Slack'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Yearly',
                'inapp_package_id' => 'yearly',
                'price' => 49.99,
                'duration' => 4,
                'description' => json_encode([
                    'An The full context of your organization’s message history at your fingertips.',
                    'An Timely info and actions in one place with unlimited integrations pair.',
                    'Face-to-face communication',
                    'Secure collaboration with outside organizans or guests from within Slack'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lifetime',
                'inapp_package_id' => 'lifetime',
                'price' => 99.99,
                'duration' => 5,
                'description' => json_encode([
                    'An The full context of your organization’s message history at your fingertips.',
                    'An Timely info and actions in one place with unlimited integrations pair.',
                    'Face-to-face communication',
                    'Secure collaboration with outside organizans or guests from within Slack'
                ]),
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Package::insert($packages);
    }
}
