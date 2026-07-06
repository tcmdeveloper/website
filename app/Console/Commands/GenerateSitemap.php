<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Article;
use App\Models\CriminalCase;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap.xml';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(
            Url::create('/')
        );

        $sitemap->add(
            Url::create('/criminal-cases')
        );

        $sitemap->add(
            Url::create('/articles')
        );


        // Criminal cases
        CriminalCase::query()
            ->each(function ($case) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('cases.show', $case)
                    )
                );
            });


        // Articles
        Article::query()
            ->each(function ($article) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('articles.show', $article)
                    )
                );
            });


        $sitemap->writeToFile(
            public_path('sitemap.xml')
        );

        $this->info('Sitemap generated successfully.');
    }
}