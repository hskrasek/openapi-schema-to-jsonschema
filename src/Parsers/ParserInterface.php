<?php namespace HSkrasek\OpenAPI\Parsers;

use HSkrasek\OpenAPI\Exceptions\ParseException;

interface ParserInterface
{
    /**
     * Parse the raw schema file into an object.
     *
     * @param string $schemaPath
     *
     * @return \stdClass
     * @throws ParseException
     */
    public function parse(string $schemaPath): \stdClass;
}
