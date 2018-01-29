<?php

namespace HSkrasek\OpenAPI\Tests;

use HSkrasek\OpenAPI\Converter;
use PHPUnit\Framework\TestCase;

class ComplexSchemaTest extends TestCase
{
    /**
     * @test
     */
    public function itConvertsAComplexSchema()
    {
        $converter = new Converter;

        $expected = $this->getSchema('complex-schema-expected.json');
        $converted = $converter->convert($this->getSchema('complex-schema.json'));

        $this->assertEquals($expected, $converted);
    }

    private function getSchema(string $schemaFile)
    {
        return json_decode(file_get_contents(__DIR__ . '/schemas/' . $schemaFile));
    }
}
