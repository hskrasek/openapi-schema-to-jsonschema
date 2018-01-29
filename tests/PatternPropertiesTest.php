<?php

namespace HSkrasek\OpenAPI\Tests;

use HSkrasek\OpenAPI\Converter;
use PHPUnit\Framework\TestCase;

class PatternPropertiesTest extends TestCase
{
    /**
     * @test
     */
    public function itConvertsAdditionalStringProperties()
    {
        $converter = new Converter(['support_pattern_properties' => true]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => false,
            'patternProperties'    => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'string',
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsAdditionalNumberProperties()
    {
        $converter = new Converter(['support_pattern_properties' => true]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => false,
            'patternProperties'    => [
                '^[a-z]*$' => [
                    'type' => 'number',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'number',
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'number',
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsAdditionalMixedProperties()
    {
        $converter = new Converter(['support_pattern_properties' => true]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => false,
            'patternProperties'    => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
                '^[A-Z]*$' => [
                    'type' => 'number',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'number',
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
                '^[A-Z]*$' => [
                    'type' => 'number',
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsAdditionalPropertiesWithMatchingObjects()
    {
        $converter = new Converter(['support_pattern_properties' => true]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => false,
            'patternProperties'    => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
                '^[A-Z]*$' => [
                    'type'       => 'object',
                    'properties' => [
                        'test' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type'       => 'object',
                'properties' => [
                    'test' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
                '^[A-Z]*$' => [
                    'type'       => 'object',
                    'properties' => [
                        'test' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsAdditionalPropertiesWithNonMatchingObjects()
    {
        $converter = new Converter(['support_pattern_properties' => true]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => [
                'type'       => 'object',
                'properties' => [
                    'test' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'patternProperties'    => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
                '^[A-Z]*$' => [
                    'type'       => 'object',
                    'properties' => [
                        'test' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type'       => 'object',
                'properties' => [
                    'test' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
                '^[A-Z]*$' => [
                    'type'       => 'object',
                    'properties' => [
                        'test' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsAdditionalPropertiesWithMatchingArray()
    {
        $converter = new Converter(['support_pattern_properties' => true]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => false,
            'patternProperties'    => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
                '^[A-Z]*$' => [
                    'type'  => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type'  => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
                '^[A-Z]*$' => [
                    'type'  => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsAdditionalPropertiesWithCompositeTypes()
    {
        $converter = new Converter(['support_pattern_properties' => true]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => false,
            'patternProperties'    => [
                '^[a-z]*$' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                        ],
                        [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'oneOf' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'integer',
                    ],
                ],
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                        ],
                        [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itDoesNothingByDefault()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'string',
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'string',
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itDoesNothingWhenPatternPropertiesIsDisabled()
    {
        $converter = new Converter(['support_pattern_properties' => false]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'string',
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'string',
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itConvertsWithACustomPatternPropertyHandler()
    {
        $handler = function ($schema) {
            $schema->patternProperties = false;

            return $schema;
        };

        $converter = new Converter(['support_pattern_properties' => true, 'pattern_properties_handler' => $handler]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'string',
            ],
            'patternProperties'  => false,
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => [
                'type' => 'string',
            ],
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itDoesNotModifyAdditionalPropertiesIfSetToTrue()
    {
        $converter = new Converter(['support_pattern_properties' => true]);

        $expected = json_decode(json_encode([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'type'                 => 'object',
            'additionalProperties' => true,
            'patternProperties'    => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'                 => 'object',
            'additionalProperties' => true,
            'x-patternProperties'  => [
                '^[a-z]*$' => [
                    'type' => 'string',
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }
}
