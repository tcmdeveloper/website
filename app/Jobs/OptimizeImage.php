<?php

namespace App\Jobs;

use App\Services\ImageOptimizer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class OptimizeImage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public mixed $model,
    ) {}

    public function handle(ImageOptimizer $optimizer): void
    {
        $optimizer->optimizeModel($this->model);
    }
}