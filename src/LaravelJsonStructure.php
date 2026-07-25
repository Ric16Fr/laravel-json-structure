<?php

declare(strict_types=1);

namespace LaravelJsonStructure\LaravelJsonStructure;

use LaravelJsonStructure\LaravelJsonStructure\Macros\RequestMacros;

class LaravelJsonStructure
{
    public function boot(): void
    {
        RequestMacros::register();
    }
}
