<?php namespace HSkrasek\OpenAPI\Command\Stages;

use HSkrasek\OpenAPI\Exceptions\NoSchemaFilesFoundException;
use League\Flysystem\FilesystemInterface;
use League\Pipeline\StageInterface;

class GetOpenAPISchemaFiles implements StageInterface
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
     * @throws \HSkrasek\OpenAPI\Exceptions\NoSchemaFilesFoundException
     */
    public function __invoke($payload)
    {
        $schemaFiles = array_filter($this->filesystem->listFiles($payload[0]), function (array $file) {
            return \in_array($file['extension'], ['json', 'yml', 'yaml'], true);
        });

        if (empty($schemaFiles)) {
            throw new NoSchemaFilesFoundException("No schema files found in $payload");
        }

        $payload[0] = $schemaFiles;

        return $payload;
    }
}
