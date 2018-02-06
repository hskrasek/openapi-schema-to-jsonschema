<?php

namespace HSkrasek\OpenAPI\Tests\Parsers;

use HSkrasek\OpenAPI\Parsers\JsonParser;
use HSkrasek\OpenAPI\Parsers\ParserFactory;
use HSkrasek\OpenAPI\Parsers\ParserInterface;
use HSkrasek\OpenAPI\Parsers\YamlParser;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsAParser()
    {
        $parser = ParserFactory::make();

        $this->assertInstanceOf(ParserInterface::class, $parser);
    }
}
