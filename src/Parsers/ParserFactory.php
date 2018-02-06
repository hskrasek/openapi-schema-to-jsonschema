<?php declare(strict_types=1);

namespace HSkrasek\OpenAPI\Parsers;

use HSkrasek\OpenAPI\Loaders\ChainedDecoderFileLoader;
use League\JsonReference\Dereferencer;
use League\JsonReference\ReferenceSerializer\InlineReferenceSerializer;

class ParserFactory
{
    /**
     * @var Dereferencer
     */
    private static $dereferencer;

    /**
     * Create a parser capable of dereferencing schemas
     *
     * @return ParserInterface
     */
    public static function make(): ParserInterface
    {
        if (null === self::$dereferencer) {
            self::$dereferencer = (new Dereferencer)->setReferenceSerializer(new InlineReferenceSerializer);
            self::$dereferencer->getLoaderManager()->registerLoader('file', new ChainedDecoderFileLoader);
        }

        return new Parser(self::$dereferencer);
    }
}
