<?php

namespace HSkrasek\OpenAPI\Tests\Parsers;

use HSkrasek\OpenAPI\Loaders\ChainedDecoderFileLoader;
use HSkrasek\OpenAPI\Parsers\Parser;
use League\JsonReference\Dereferencer;
use League\JsonReference\ReferenceSerializer\InlineReferenceSerializer;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    public function setUp()
    {
        parent::setUp();
        $dereferencer = (new Dereferencer)->setReferenceSerializer(new InlineReferenceSerializer);
        $dereferencer->getLoaderManager()->registerLoader('file', new ChainedDecoderFileLoader);

        $this->parser = new Parser($dereferencer);
    }

    /**
     * @test
     */
    public function itParsesAJsonSchemaFile()
    {
        $schema = $this->parser->parse(__DIR__ . '/../schemas/schema.json');

        $this->assertInstanceOf('stdClass', $schema);
    }

    /**
     * @test
     */
    public function itParsesAYamlSchemaFile()
    {
        $schema = $this->parser->parse(__DIR__ . '/../schemas/schema.yaml');

        $this->assertInstanceOf('stdClass', $schema);
    }

    /**
     * @test
     */
    public function itParsesReferencedSchemaFiles()
    {
        $schema = $this->parser->parse(__DIR__ . '/../schemas/referenced-schema.json');

        $this->assertInstanceOf('stdClass', $schema);
    }
}
