<?php namespace HSkrasek\OpenAPI\Parsers;

use HSkrasek\OpenAPI\Exceptions\ParseException;

interface ParserInterface
{
    /**
     * Parse the raw schema file into an object.
     *
     * @param string $schemaContent
     *
     * @return \stdClass
     * @throws ParseException
     */
    public function parse(string $schemaContent): \stdClass;
}
