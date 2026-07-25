<?php

declare(strict_types=1);

namespace LaravelJsonStructure\LaravelJsonStructure\Macros;

use Illuminate\Http\Request;
use LaravelJsonStructure\LaravelJsonStructure\Logic\JSONStructure;

class RequestMacros
{
    public static function register(): void
    {
        Request::macro('validateWithJSONStructure', function (JSONStructure $structure) {
            /** @var Request $this */
            return JSONStructure::validateJSON($this, $structure);
        });

        Request::macro('validateWithUncheckedJSONStructure', function (array $structure) {
            /** @var Request $this */
            return JSONStructure::validateJSONWithUncheckedStructure($this, $structure);
        });
    }
}
