<?php

namespace HSkrasek\OpenAPI\Tests;

use HSkrasek\OpenAPI\Converter;
use PHPUnit\Framework\TestCase;

class StringTypesTest extends TestCase
{
    /**
     * @test
     */
    public function itConvertsStringTypes()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type' => 'string',
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsByteTypes()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
            'format'  => 'byte',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type' => 'byte',
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsBinaryTypes()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
            'format'  => 'binary',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type' => 'binary',
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsDateTimeTypes()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
            'format'  => 'date-time',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type' => 'dateTime',
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsDateTypes()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
            'format'  => 'date',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type' => 'date',
        ])));

        $this->assertEquals($expected, $converted);

        $converter = new Converter(['convert_date' => true]);

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
            'format'  => 'date-time',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type' => 'date',
        ])));

        $this->assertEquals($expected, $converted);

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
            'format'  => 'date-time',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'   => 'string',
            'format' => 'date',
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsPasswordTypes()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
            'format'  => 'password',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type' => 'password',
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsCustomTypes()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
            'format'  => 'email',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'   => 'string',
            'format' => 'email',
        ])));

        $this->assertEquals($expected, $converted);
    }
}
