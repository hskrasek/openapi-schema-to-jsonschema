<?php

namespace HSkrasek\OpenAPI\Tests\Parsers;

use HSkrasek\OpenAPI\Parsers\JsonParser;
use PHPUnit\Framework\TestCase;

class JsonParserTest extends TestCase
{
    /**
     * @test
     */
    public function itParsesProperlyFormattedJson()
    {
        $parser = new JsonParser;

        $this->assertInstanceOf('stdClass', $parser->parse('{"foo": "bar"}'));
    }

    /**
     * @test
     *
     * @expectedException \HSKrasek\OpenAPI\Exceptions\ParseException
     * @expectedExceptionMessage Unable to parse file. Reason: Syntax error
     */
    public function itThrowsAnExceptionWhenIncorrectlyFormattedJsonIsPassedIn()
    {
        $parser = new JsonParser;

        $parser->parse("{'foo': 'bar'}");
    }
}
