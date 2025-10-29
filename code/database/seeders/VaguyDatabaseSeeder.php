<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Vaguy Database Seeder
 *
 * This seeder loads the existing SQL file to populate the database
 * with the production data from vaguy2022_influencity.sql
 */
class VaguyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Loading Vaguy database from SQL file...');

        // Path to the SQL file
        $sqlFile = database_path('migrations/vaguy2022_influencity.sql');

        // Check if the SQL file exists
        if (!File::exists($sqlFile)) {
            $this->command->error('SQL file not found at: ' . $sqlFile);
            return;
        }

        try {
            // Disable foreign key checks to avoid constraint issues during import
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Read the SQL file content
            $sql = File::get($sqlFile);

            // Remove any existing database name references and USE statements
            $sql = preg_replace('/^USE\s+`[^`]+`;/mi', '', $sql);
            $sql = preg_replace('/CREATE DATABASE[^;]+;/mi', '', $sql);

            // Split the SQL into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                function ($statement) {
                    return !empty($statement) && !preg_match('/^\s*(--|\/\*|#)/', $statement);
                }
            );

            $this->command->info('Found ' . count($statements) . ' SQL statements to execute...');

            // Execute each statement
            $progressBar = $this->command->getOutput()->createProgressBar(count($statements));
            $progressBar->start();

            foreach ($statements as $index => $statement) {
                try {
                    if (trim($statement)) {
                        // Skip problematic statements that might cause issues
                        if (
                            strpos($statement, 'CREATE DATABASE') !== false ||
                            strpos($statement, 'USE ') === 0 ||
                            strpos($statement, '/*!40') !== false ||
                            strpos($statement, 'SET @@') !== false
                        ) {
                            continue;
                        }

                        DB::unprepared($statement . ';');
                    }
                } catch (\Exception $e) {
                    $this->command->warn("\nSkipping problematic statement at index {$index}: " . substr($statement, 0, 100) . '...');
                    $this->command->warn('Error: ' . $e->getMessage());
                }

                $progressBar->advance();
            }

            $progressBar->finish();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->command->newLine();
            $this->command->info('✅ Vaguy database seeded successfully!');

            // Display some statistics
            $this->displayDatabaseStats();

        } catch (\Exception $e) {
            // Re-enable foreign key checks even if there's an error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->command->error('❌ Error loading database: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Display database statistics after seeding
     */
    private function displayDatabaseStats()
    {
        try {
            $tables = [
                'users' => 'Users',
                'influencers' => 'Influencers',
                'admin_notifications' => 'Admin Notifications',
                'campains' => 'Campaigns',
                'services' => 'Services',
                'orders' => 'Orders',
                'hirings' => 'Hirings',
                'transactions' => 'Transactions',
                'gateways' => 'Payment Gateways',
                'languages' => 'Languages',
                'categories' => 'Categories',
                'support_tickets' => 'Support Tickets',
                'general_settings' => 'General Settings'
            ];

            $this->command->newLine();
            $this->command->info('📊 Database Statistics:');
            $this->command->info(str_repeat('-', 40));

            foreach ($tables as $table => $displayName) {
                try {
                    $count = DB::table($table)->count();
                    $this->command->info(sprintf('%-20s: %d records', $displayName, $count));
                } catch (\Exception $e) {
                    // Table might not exist, skip it
                    continue;
                }
            }

            $this->command->newLine();
            $this->command->info('🎉 Database is ready for use!');

        } catch (\Exception $e) {
            $this->command->warn('Could not display statistics: ' . $e->getMessage());
        }
    }
}