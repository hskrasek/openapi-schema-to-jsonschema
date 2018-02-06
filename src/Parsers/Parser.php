<?php namespace HSkrasek\OpenAPI\Parsers;

use HSkrasek\OpenAPI\Exceptions\ParseException;
use League\JsonReference\DecodingException;
use League\JsonReference\DereferencerInterface;

final class Parser implements ParserInterface
{
    /**
     * @var DereferencerInterface
     */
    private $dereferencer;

    public function __construct(DereferencerInterface $dereferencer)
    {
        $this->dereferencer = $dereferencer;
    }

    /**
     * @inheritDoc
     */
    public function parse(string $schemaPath): \stdClass
    {
        try {
            return $this->dereferencer->dereference('file://' . $schemaPath);
        } catch (DecodingException $decodingException) {
            throw new ParseException($decodingException->getMessage());
        }
    }
}
