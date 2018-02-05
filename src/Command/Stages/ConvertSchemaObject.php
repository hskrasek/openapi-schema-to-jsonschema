<?php namespace HSkrasek\OpenAPI\Command\Stages;

use HSkrasek\OpenAPI\Converter;
use League\Pipeline\StageInterface;

class ConvertSchemaObject implements StageInterface
{
    /**
     * @var Converter
     */
    private $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        return $this->converter->convert($payload);
    }
}
