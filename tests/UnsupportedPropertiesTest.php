<?php
/**
 * Created by PhpStorm.
 * User: hunterskrasek
 * Date: 1/29/18
 * Time: 1:05 PM
 */

namespace HSkrasek\OpenAPI\Tests;

use HSkrasek\OpenAPI\Converter;
use PHPUnit\Framework\TestCase;

class UnsupportedPropertiesTest extends TestCase
{
    /**
     * @test
     */
    public function itRemovesDiscriminatorByDefault()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'oneOf'   => [
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'oneOf'         => [
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                [
                    'type'       => 'object',
                    'required'   => ['foo'],
                    'properties' => [
                        'foo' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
            'discriminator' => [
                'propertyName' => 'foo',
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itRemovesReadOnlyByDefault()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'type'       => 'object',
            'properties' => [
                'readOnly' => [
                    'type' => 'string',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'       => 'object',
            'properties' => [
                'readOnly' => [
                    'type'     => 'string',
                    'readOnly' => true,
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itRemovesWriteOnlyByDefault()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'type'       => 'object',
            'properties' => [
                'test' => [
                    'type'   => 'string',
                    'format' => 'password',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'       => 'object',
            'properties' => [
                'test' => [
                    'type'      => 'string',
                    'format'    => 'password',
                    'writeOnly' => true,
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itRemovesXmlByDefault()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'type'       => 'object',
            'properties' => [
                'foo' => [
                    'type' => 'string',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'       => 'object',
            'properties' => [
                'foo' => [
                    'type' => 'string',
                    'xml'  => [
                        'attribute' => true,
                    ],
                ],
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itRemovesExternalDocsByDefault()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'type'       => 'object',
            'properties' => [
                'foo' => [
                    'type' => 'string',
                ],
            ],
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'         => 'object',
            'properties'   => [
                'foo' => [
                    'type' => 'string',
                ],
            ],
            'externalDocs' => [
                'url' => 'http://foo.bar',
            ],
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itRemovesExampleByDefault()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'    => 'string',
            'example' => 'foo',
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itRemovesDeprecatedByDefault()
    {
        $converter = new Converter;

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'string',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'       => 'string',
            'deprecated' => true,
        ])));

        $this->assertEquals($expected, $converted);
    }

    /**
     * @test
     */
    public function itRetainsFields()
    {
        $converter = new Converter(['keep_not_supported' => ['readOnly', 'discriminator']]);

        $expected = json_decode(json_encode([
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'type'    => 'object',
            'properties'    => [
                'readOnly'    => [
                    'type'     => 'string',
                    'readOnly' => true,
                ],
                'anotherProp' => [
                    'type'       => 'object',
                    'properties' => [
                        'writeOnly' => [
                            'type'      => 'string',
                        ],
                    ],
                ],
            ],
            'discriminator' => 'bar',
        ]));

        $converted = $converter->convert(json_decode(json_encode([
            'type'          => 'object',
            'properties'    => [
                'readOnly'    => [
                    'type'     => 'string',
                    'readOnly' => true,
                    'example'  => 'foo',
                ],
                'anotherProp' => [
                    'type'       => 'object',
                    'properties' => [
                        'writeOnly' => [
                            'type'      => 'string',
                            'writeOnly' => true,
                        ],
                    ],
                ],
            ],
            'discriminator' => 'bar',
        ])));

        $this->assertEquals($expected, $converted);
    }
}
