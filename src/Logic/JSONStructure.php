<?php

declare(strict_types=1);

namespace LaravelJsonStructure\LaravelJsonStructure\Logic;

use Illuminate\Http\Request;
use JsonStructure\InstanceValidator;
use JsonStructure\SchemaValidator;
use JsonStructure\ValidationError;
use RuntimeException;
use ValueError;

class JSONStructure
{
    /** @var array<string, mixed> */
    private array $structure;

    /**
     * @param  array<string, mixed>  $structure
     */
    private function __construct(array $structure)
    {
        $this->structure = $structure;
    }

    /**
     * Creates a JSON-Structure Definition from an array Object. Thats usually the case when the structure definition is already defined
     * in your client or reccieved by an external API Endpoint.
     * Validates that the files produces a correct Structure document before returning it.
     *
     * @param  array<string, mixed>  $structure  the JSONStructure defined in an array
     * @return JSONStructure JSONStructure to be used in verifying all further Request payloads of this type.
     *
     * @throws ValueError when the structure is not a valid JSON Structure document
     */
    public static function createFromObject(array $structure): JSONStructure
    {
        $validator = new SchemaValidator(extended: config('json_structure.extended_validation', true));
        $errors = $validator->validate($structure);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                throw new ValueError(message: $error->message, code: intval($error->code));
            }
        }

        return new self($structure);
    }

    /**
     * Creates a JSON-Structure Definition from an JSON file. That is usually the case when its send by another developer.
     * Validates that the files produces a correct Structure document before returning it.
     *
     * @param  string  $pathToFile  the path to the JSON file with the structure
     * @return JSONStructure the structure to be used in verifying all further Request payloads of this type.
     *
     * @throws RuntimeException when the file cannot be read
     * @throws ValueError when the structure is not a valid JSON Structure document
     */
    public static function createFromFile(string $pathToFile): JSONStructure
    {
        $contents = file_get_contents($pathToFile);

        if ($contents === false) {
            throw new RuntimeException("Unable to read JSON Structure file at [{$pathToFile}].");
        }

        /** @var array<string, mixed> $json_array */
        $json_array = json_decode($contents, true);
        $validator = new SchemaValidator(extended: config('json_structure.extended_validation', true));
        $errors = $validator->validate($json_array);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                throw new ValueError(message: $error->message, code: intval($error->code));
            }
        }

        return new self($json_array);
    }

    /**
     * Validates a reccieved JSON from a request against the JSONStructure created previously.
     *
     * @param  Request  $instance  the Laravel Request to be validated
     * @param  JSONStructure  $structure  the previously created Structure object
     * @return array<int, ValidationError> validation errors, empty when the request satisfies the structure
     */
    public static function validateJSON(Request $instance, JSONStructure $structure): array
    {
        $validator = new InstanceValidator($structure->structure, extended: config('json_structure.extended_validation', true));

        return $validator->validate($instance->array());
    }

    /**
     * Validates a reccieved JSON from a request against an array containing the JSONStructure document on the fly. The Structure
     * document will NOT be validated here, so if the structure document is malformed, an Error will occour.
     *
     * @param  Request  $instance  the Laravel Request to be validated
     * @param  array<string, mixed>  $structure  an unchecked Structure object
     * @return array<int, ValidationError> validation errors, empty when the request satisfies the structure
     */
    public static function validateJSONWithUncheckedStructure(Request $instance, array $structure): array
    {
        $validator = new InstanceValidator($structure, extended: config('json_structure.extended_validation', true));

        return $validator->validate($instance->array());
    }
}
