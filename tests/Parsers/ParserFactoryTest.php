<?php

namespace HSkrasek\OpenAPI\Tests\Parsers;

use HSkrasek\OpenAPI\Parsers\JsonParser;
use HSkrasek\OpenAPI\Parsers\ParserFactory;
use HSkrasek\OpenAPI\Parsers\YamlParser;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsAJsonParserWhenGivenAJsonFormat()
    {
        $parser = ParserFactory::make('json');

        $this->assertInstanceOf(JsonParser::class, $parser);
    }

    /**
     * @test
     */
    public function itReturnsAYamlParserWhenGivenAYamlFormat()
    {
        $parser = ParserFactory::make('yaml');

        $this->assertInstanceOf(YamlParser::class, $parser);
    }
}
