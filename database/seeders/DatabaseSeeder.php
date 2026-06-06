<?php

namespace Database\Seeders;

use Database\Seeders\ArticleSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
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
        $this->call([

            UserSeeder::class,
            CategorySeeder::class,
            ArticleSeeder::class,
            TranscriptionSeeder::class,
            EmailChangeRequestSeeder::class,
            FailedJobSeeder::class,
            JobBatchSeeder::class,
            JobSeeder::class,
            TermsVersionSeeder::class,
            
            
        ]);
    }
}
