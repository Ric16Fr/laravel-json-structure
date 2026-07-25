<?php

declare(strict_types=1);

namespace LaravelJsonStructure\LaravelJsonStructure\Tests;

use LaravelJsonStructure\LaravelJsonStructure\LaravelJsonStructureServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelJsonStructureServiceProvider::class,
        ];
    }
}
