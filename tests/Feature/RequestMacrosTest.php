<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use JsonStructure\ValidationError;
use LaravelJsonStructure\LaravelJsonStructure\Logic\JSONStructure;

// Shared helpers (personSchema, jsonRequest, errorCodes) live in tests/Pest.php.
// The macros are wired through the service provider's boot(), so booting the
// package (via TestCase) is what registers them on the Request.

describe('RequestMacros registration', function () {
    it('registers the validateWithJSONStructure macro on the Request', function () {
        expect(Request::hasMacro('validateWithJSONStructure'))->toBeTrue();
    });

    it('registers the validateWithUncheckedJSONStructure macro on the Request', function () {
        expect(Request::hasMacro('validateWithUncheckedJSONStructure'))->toBeTrue();
    });
});

describe('Request::validateWithJSONStructure', function () {
    it('returns no errors when the request satisfies the structure', function () {
        $structure = JSONStructure::createFromObject(personSchema());
        $request = jsonRequest(['name' => 'Ada', 'age' => 30, 'email' => 'ada@example.com']);

        expect($request->validateWithJSONStructure($structure))
            ->toBeArray()
            ->toBe([]);
    });

    it('returns validation errors when the request violates the structure', function () {
        $structure = JSONStructure::createFromObject(personSchema());
        // Missing the required "name" and "age" has the wrong type.
        $request = jsonRequest(['age' => 'not-a-number']);

        $errors = $request->validateWithJSONStructure($structure);

        expect($errors)->not->toBeEmpty()
            ->and($errors)->each->toBeInstanceOf(ValidationError::class)
            ->and(errorCodes($errors))->toContain('INSTANCE_REQUIRED_PROPERTY_MISSING');
    });
});

describe('Request::validateWithUncheckedJSONStructure', function () {
    it('returns no errors for a valid request against a raw schema array', function () {
        $request = jsonRequest(['name' => 'Ada', 'age' => 30, 'email' => 'ada@example.com']);

        expect($request->validateWithUncheckedJSONStructure(personSchema()))
            ->toBeArray()
            ->toBe([]);
    });

    it('returns validation errors for an invalid request against a raw schema array', function () {
        // Missing the required "name".
        $request = jsonRequest(['age' => 30]);

        $errors = $request->validateWithUncheckedJSONStructure(personSchema());

        expect($errors)->not->toBeEmpty()
            ->and($errors)->each->toBeInstanceOf(ValidationError::class)
            ->and(errorCodes($errors))->toContain('INSTANCE_REQUIRED_PROPERTY_MISSING');
    });
});
