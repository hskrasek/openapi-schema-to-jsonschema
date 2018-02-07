<?php namespace HSkrasek\OpenAPI\Command\Stages;

use League\JsonReference\DereferencerInterface;
use League\JsonReference\Loader\ArrayLoader;
use League\Pipeline\Pipeline;
use League\Pipeline\StageInterface;

class ProcessSchemaFiles implements StageInterface
{
    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @var DereferencerInterface
     */
    private $dereferencer;

    public function __construct(Pipeline $pipeline, DereferencerInterface $dereferencer)
    {
        $this->pipeline     = $pipeline;
        $this->dereferencer = $dereferencer;
    }

    /**
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        $payload[0] = array_reduce($payload[0], function (array $carry, array $openApiSchemaFile) {
            $carry['/' . $openApiSchemaFile['basename']] = $this->pipeline->process($openApiSchemaFile);

            return $carry;
        }, []);

        $this->dereferencer->getLoaderManager()->registerLoader('file', new ArrayLoader($payload[0]));

        $payload[0] = array_map(function ($schema) {
            return $this->dereferencer->dereference($schema, 'file://');
        }, $payload[0]);

        return $payload;
    }
}
