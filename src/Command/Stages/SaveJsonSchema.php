<?php namespace HSkrasek\OpenAPI\Command\Stages;

use League\Flysystem\FilesystemInterface;
use League\Pipeline\StageInterface;

class SaveJsonSchema implements StageInterface
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
        foreach ($payload[0] as $filename => $jsonSchema) {
            $schemaPath = $payload[1] . DIRECTORY_SEPARATOR . pathinfo($filename, PATHINFO_FILENAME) . '.json';

            if ($this->filesystem->has($schemaPath)) {
                $this->filesystem->delete($schemaPath);
            }

            $this->filesystem->put(
                $schemaPath,
                json_encode($jsonSchema, JSON_PRETTY_PRINT)
            );
        }

        return \count($payload[0]);
    }
}
