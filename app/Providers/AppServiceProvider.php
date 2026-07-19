<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Character;
use App\Models\CriminalCase;
use App\Models\Playlist;
use App\Services\Courts\CourtProviderFactory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            CourtProviderFactory::class,
            function () {
                return new CourtProviderFactory();
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pagination defaults
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');


        // Morph map relations
        Relation::enforceMorphMap([
            'criminal_case' => CriminalCase::class,
            'category' => Category::class,
            'article' => Article::class,
            'character' => Character::class,
            'playlist' => Playlist::class,
        ]);
    }
}
