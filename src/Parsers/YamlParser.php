<?php declare(strict_types=1);

namespace HSkrasek\OpenAPI\Parsers;

use HSkrasek\OpenAPI\Exceptions\ParseException;
use Symfony\Component\Yaml\Exception\ParseException as SymfonyParseException;
use Symfony\Component\Yaml\Yaml;

final class YamlParser implements ParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $schemaContent): \stdClass
    {
        try {
            // @codingStandardsIgnoreStart
            return Yaml::parse(
                $schemaContent,
                Yaml::PARSE_OBJECT | Yaml::PARSE_OBJECT_FOR_MAP | Yaml::PARSE_DATETIME | Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE
            );
            // @codingStandardsIgnoreEnd
        } catch (SymfonyParseException $parseException) {
            throw new ParseException($parseException->getMessage());
        }
    }
}
