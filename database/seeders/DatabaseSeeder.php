<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // SEED THE APPLICATION'S FOLDER
    
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CriminalCaseSeeder::class,
            CategorySeeder::class,
            ArticleSeeder::class,
            ArticleImageSeeder::class,
            TranscriptionSeeder::class,
            EmailChangeRequestSeeder::class,
            FailedJobSeeder::class,
            JobBatchSeeder::class,
            JobSeeder::class,
            TermsVersionSeeder::class,
            JailCallLogSeeder::class,
            VideoSeeder::class,
            TranscriptSegmentSeeder::class,
            DocumentSeeder::class,
            DocumentPageSeeder::class,
        ]);
    }
}
