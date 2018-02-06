<?php namespace HSkrasek\OpenAPI\Command\Stages;

use HSkrasek\OpenAPI\Parsers\ParserFactory;
use League\Flysystem\FilesystemInterface;
use League\Pipeline\StageInterface;

class ParseSchemaFile implements StageInterface
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        return ParserFactory::make()->parse(
            $this->filesystem->getAdapter()->getPathPrefix() . DIRECTORY_SEPARATOR . $payload['path']
        );
    }
}
