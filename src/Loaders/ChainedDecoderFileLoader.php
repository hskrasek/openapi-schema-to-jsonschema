<?php namespace HSkrasek\OpenAPI\Loaders;

use League\JsonReference\Decoder\JsonDecoder;
use League\JsonReference\Decoder\YamlDecoder;
use League\JsonReference\DecoderInterface;
use League\JsonReference\DecodingException;
use League\JsonReference\LoaderInterface;
use League\JsonReference\SchemaLoadingException;

class ChainedDecoderFileLoader implements LoaderInterface
{
    /**
     * @var DecoderInterface
     */
    private $jsonDecoder;

    /**
     * @var DecoderInterface
     */
    private $yamlDecoder;

    public function __construct(DecoderInterface $jsonDecoder = null, DecoderInterface $yamlDecoder = null)
    {
        $this->jsonDecoder = $jsonDecoder ?: new JsonDecoder;
        $this->yamlDecoder = $yamlDecoder ?: new YamlDecoder;
    }

    /**
     * @inheritDoc
     */
    public function load($path)
    {
        $uri = 'file://' . $path;

        if (!file_exists($uri)) {
            throw SchemaLoadingException::notFound($uri);
        }

        $schema = file_get_contents($uri);

        try {
            return $this->jsonDecoder->decode($schema);
        } catch (DecodingException $e) {
            return $this->yamlDecoder->decode($schema);
        }
    }
}
