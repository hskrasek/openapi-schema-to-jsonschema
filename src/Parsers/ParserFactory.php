<?php declare(strict_types=1);

namespace HSkrasek\OpenAPI\Parsers;

class ParserFactory
{
    /**
     * Create a parser based on the provided format
     *
     * @param string $format
     *
     * @return ParserInterface
     */
    public static function make(string $format): ParserInterface
    {
        if (strtolower($format) === 'json') {
            return new JsonParser;
        }

        return new YamlParser;
    }
}
