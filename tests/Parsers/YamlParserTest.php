<?php

namespace HSkrasek\OpenAPI\Tests\Parsers;

use HSkrasek\OpenAPI\Parsers\YamlParser;
use PHPUnit\Framework\TestCase;

class YamlParserTest extends TestCase
{
    /**
     * @test
     */
    public function itParsesProperlyFormattedYaml()
    {
        $parser = new YamlParser;

        $yaml = <<<'EOL'
foo:
  bar:
   baz:
    - x
    - y
EOL;

        $this->assertInstanceOf('stdClass', $parser->parse($yaml));
    }

    /**
     * @test
     *
     * @expectedException \HSkrasek\OpenAPI\Exceptions\ParseException
     * @expectedExceptionMessage Unable to parse file. Reason: Unable to parse at line 3 (near " foo: bar").
     */
    public function itThrowsAnExceptionWhenPassedPoorlyFormattedYaml()
    {
        $parser = new YamlParser;

        $parser->parse(<<<'EOL'
collection:
- key: foo
 foo: bar
EOL
        );
    }
}
