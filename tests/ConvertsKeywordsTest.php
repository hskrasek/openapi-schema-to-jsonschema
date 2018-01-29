<?php namespace HSkrasek\OpenAPI\Tests;

use HSkrasek\OpenAPI\Converter;
use PHPUnit\Framework\TestCase;

class ConvertsKeywordsTest extends TestCase
{
    /**
     * @test
     */
    public function itIteratesAllOfsAndConvertsTypes()
    {
        $converter = new Converter;

        $expectedSchema = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'allOf'   => [
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type'   => 'integer',
                            'format' => 'int64',
                        ],
                    ],
                ],
                [
                    'allOf' => [
                        [
                            'type'   => 'number',
                            'format' => 'double',
                        ],
                    ],
                ],
            ],
        ]));

        $convertedSchema = $converter->convert(json_decode(json_encode([
            'allOf' => [
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type' => 'long',
                        ],
                    ],
                ],
                [
                    'allOf' => [
                        [
                            'type' => 'double',
                        ],
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expectedSchema, $convertedSchema);
    }

    /**
     * @test
     */
    public function itIteratesAnyOfsAndConvertsTypes()
    {
        $converter = new Converter;

        $expectedSchema = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'anyOf'   => [
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type'   => 'integer',
                            'format' => 'int64',
                        ],
                    ],
                ],
                [
                    'anyOf' => [
                        [
                            'type'       => 'object',
                            'properties' => [
                                'bar' => [
                                    'type'   => 'number',
                                    'format' => 'double',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]));

        $convertedSchema = $converter->convert(json_decode(json_encode([
            'anyOf' => [
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type' => 'long',
                        ],
                    ],
                ],
                [
                    'anyOf' => [
                        [
                            'type'       => 'object',
                            'properties' => [
                                'bar' => [
                                    'type' => 'double',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expectedSchema, $convertedSchema);
    }

    /**
     * @test
     */
    public function itIteratesOneOfsAndConvertsTypes()
    {
        $converter = new Converter;

        $expectedSchema = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'oneOf'   => [
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type'   => 'integer',
                            'format' => 'int64',
                        ],
                    ],
                ],
                [
                    'oneOf' => [
                        [
                            'type'       => 'object',
                            'properties' => [
                                'bar' => [
                                    'type'   => 'number',
                                    'format' => 'double',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]));

        $convertedSchema = $converter->convert(json_decode(json_encode([
            'oneOf' => [
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type' => 'long',
                        ],
                    ],
                ],
                [
                    'oneOf' => [
                        [
                            'type'       => 'object',
                            'properties' => [
                                'bar' => [
                                    'type' => 'double',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expectedSchema, $convertedSchema);
    }

    /**
     * @test
     */
    public function itConvertsTypesInNot()
    {
        $converter = new Converter;

        $expectedSchema = json_decode(json_encode([
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'type'       => 'object',
            'properties' => [
                'not' => [
                    'type'      => 'string',
                    'format'    => 'password',
                    'minLength' => 8,
                ],
            ],
        ]));

        $convertedSchema = $converter->convert(json_decode(json_encode([
            'type'       => 'object',
            'properties' => [
                'not' => [
                    'type'      => 'password',
                    'minLength' => 8,
                ],
            ],
        ])));

        $this->assertEquals($expectedSchema, $convertedSchema);
    }

    /**
     * @test
     */
    public function itConvertsNot()
    {
        $converter = new Converter;

        $expectedSchema = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'object',
            'not'     => [
                'type'      => 'string',
                'format'    => 'password',
                'minLength' => 8,
            ],
        ]));

        $convertedSchema = $converter->convert(json_decode(json_encode([
            'type' => 'object',
            'not'  => [
                'type'      => 'password',
                'minLength' => 8,
            ],
        ])));

        $this->assertEquals($expectedSchema, $convertedSchema);
    }

    /**
     * @test
     */
    public function itConvertsNestedKeywords()
    {
        $converter = new Converter;

        $expectedSchema = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'anyOf'   => [
                [
                    'allOf' => [
                        [
                            'type'       => 'object',
                            'properties' => [
                                'foo' => [
                                    'type' => ['string', 'null'],
                                ],
                            ],
                        ],
                        [
                            'type'       => 'object',
                            'properties' => [
                                'bar' => [
                                    'type' => ['integer', 'null'],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'type'       => 'object',
                    'properties' => [
                        'foo' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                [
                    'not' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ]));

        $convertedSchema = $converter->convert(json_decode(json_encode([
            'anyOf' => [
                [
                    'allOf' => [
                        [
                            'type'       => 'object',
                            'properties' => [
                                'foo' => [
                                    'type'     => 'string',
                                    'nullable' => true,
                                ],
                            ],
                        ],
                        [
                            'type'       => 'object',
                            'properties' => [
                                'bar' => [
                                    'type'     => 'integer',
                                    'nullable' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'type'       => 'object',
                    'properties' => [
                        'foo' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                [
                    'not' => [
                        'type'    => 'string',
                        'example' => 'foobar',
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expectedSchema, $convertedSchema);
    }
}
