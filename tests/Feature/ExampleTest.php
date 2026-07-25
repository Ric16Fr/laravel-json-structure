<?php

declare(strict_types=1);

use LaravelJsonStructure\LaravelJsonStructure\LaravelJsonStructure;

it('resolves the singleton', function () {
    expect(app(LaravelJsonStructure::class))->toBeInstanceOf(LaravelJsonStructure::class);
});

it('returns the same instance from the container', function () {
    expect(app(LaravelJsonStructure::class))->toBe(app(LaravelJsonStructure::class));
});

it('merges the package config', function () {
    expect(config('json_structure.extended_validation'))->toBeTrue();
});
