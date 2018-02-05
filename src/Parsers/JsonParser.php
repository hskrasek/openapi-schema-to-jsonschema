<?php declare(strict_types=1);

namespace HSkrasek\OpenAPI\Parsers;

use HSkrasek\OpenAPI\Exceptions\ParseException;

final class JsonParser implements ParserInterface
{
    /**
     * @inheritdoc
     */
    public function parse(string $schemaContent): \stdClass
    {
        $parsedSchema = \json_decode($schemaContent);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ParseException(json_last_error_msg());
        }

        return $parsedSchema;
    }
}
