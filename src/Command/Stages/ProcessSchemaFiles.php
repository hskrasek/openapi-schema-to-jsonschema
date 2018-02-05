<?php namespace HSkrasek\OpenAPI\Command\Stages;

use League\Pipeline\Pipeline;
use League\Pipeline\StageInterface;

class ProcessSchemaFiles implements StageInterface
{
    /**
     * @var Pipeline
     */
    private $pipeline;

    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        $payload[0] = array_reduce($payload[0], function (array $carry, array $openApiSchemaFile) {
            $carry[$openApiSchemaFile['filename'] . '.json'] = $this->pipeline->process($openApiSchemaFile);

            return $carry;
        }, []);

        return $payload;
    }
}
