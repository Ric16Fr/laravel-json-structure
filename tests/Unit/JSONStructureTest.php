<?php

declare(strict_types=1);

use JsonStructure\ValidationError;
use LaravelJsonStructure\LaravelJsonStructure\Logic\JSONStructure;

// Shared helpers (personSchema, personSchemaPath, invalidSchemaPath, jsonRequest,
// errorCodes) live in tests/Pest.php.

/*
|--------------------------------------------------------------------------
| createFromObject()
|--------------------------------------------------------------------------
*/

describe('JSONStructure::createFromObject', function () {
    it('builds a structure from a valid schema array', function () {
        $structure = JSONStructure::createFromObject(personSchema());

        expect($structure)->toBeInstanceOf(JSONStructure::class);
    });

    it('throws a ValueError when the schema array is invalid', function () {
        $invalid = personSchema();
        $invalid['required'] = 'name'; // "required" must be an array, not a string.

        expect(fn () => JSONStructure::createFromObject($invalid))
            ->toThrow(ValueError::class, "'required' must be an array.");
    });
});

/*
|--------------------------------------------------------------------------
| createFromFile()
|--------------------------------------------------------------------------
*/

describe('JSONStructure::createFromFile', function () {
    it('builds a structure from a valid schema file', function () {
        $structure = JSONStructure::createFromFile(personSchemaPath());

        expect($structure)->toBeInstanceOf(JSONStructure::class);
    });

    it('produces the same structure whether loaded from an array or a file', function () {
        $fromArray = JSONStructure::createFromObject(personSchema());
        $fromFile = JSONStructure::createFromFile(personSchemaPath());

        // A valid instance validates cleanly against both, proving they carry
        // the same underlying schema.
        $request = jsonRequest(['name' => 'Ada', 'age' => 30, 'email' => 'ada@example.com']);

        expect(JSONStructure::validateJSON($request, $fromArray))->toBe([])
            ->and(JSONStructure::validateJSON($request, $fromFile))->toBe([]);
    });

    it('throws a ValueError when the schema file is invalid', function () {
        expect(fn () => JSONStructure::createFromFile(invalidSchemaPath()))
            ->toThrow(ValueError::class, 'is not a recognized');
    });
});

/*
|--------------------------------------------------------------------------
| validateJSON()
|--------------------------------------------------------------------------
*/

describe('JSONStructure::validateJSON', function () {
    it('returns an empty array for a request that satisfies the structure', function () {
        $structure = JSONStructure::createFromObject(personSchema());
        $request = jsonRequest(['name' => 'Ada', 'age' => 30, 'email' => 'ada@example.com']);

        expect(JSONStructure::validateJSON($request, $structure))
            ->toBeArray()
            ->toBe([]);
    });

    it('accepts a payload that omits the optional properties', function () {
        $structure = JSONStructure::createFromObject(personSchema());
        $request = jsonRequest(['name' => 'Ada']);

        expect(JSONStructure::validateJSON($request, $structure))->toBe([]);
    });

    it('reports a missing required property', function () {
        $structure = JSONStructure::createFromObject(personSchema());
        $request = jsonRequest(['email' => 'ada@example.com']);

        $errors = JSONStructure::validateJSON($request, $structure);

        expect($errors)->not->toBeEmpty()
            ->and($errors)->each->toBeInstanceOf(ValidationError::class)
            ->and(errorCodes($errors))->toContain('INSTANCE_REQUIRED_PROPERTY_MISSING');
    });

    it('reports a property with the wrong type', function () {
        $structure = JSONStructure::createFromObject(personSchema());
        $request = jsonRequest(['name' => 'Ada', 'age' => 'not-a-number']);

        $errors = JSONStructure::validateJSON($request, $structure);

        expect($errors)->not->toBeEmpty()
            ->and($errors)->each->toBeInstanceOf(ValidationError::class)
            ->and(errorCodes($errors))->toContain('INSTANCE_INTEGER_EXPECTED');
    });
});

/*
|--------------------------------------------------------------------------
| validateJSONWithUncheckedStructure()
|--------------------------------------------------------------------------
|
| This method skips the SchemaValidator step and validates the request instance
| directly against the raw schema array supplied by the caller.
*/

describe('JSONStructure::validateJSONWithUncheckedStructure', function () {
    it('returns an empty array for a valid instance', function () {
        $request = jsonRequest(['name' => 'Ada', 'age' => 30, 'email' => 'ada@example.com']);

        expect(JSONStructure::validateJSONWithUncheckedStructure($request, personSchema()))
            ->toBeArray()
            ->toBe([]);
    });

    it('reports validation errors for an invalid instance', function () {
        $request = jsonRequest(['age' => 30]);

        $errors = JSONStructure::validateJSONWithUncheckedStructure($request, personSchema());

        expect($errors)->not->toBeEmpty()
            ->and($errors)->each->toBeInstanceOf(ValidationError::class)
            ->and(errorCodes($errors))->toContain('INSTANCE_REQUIRED_PROPERTY_MISSING');
    });
});
