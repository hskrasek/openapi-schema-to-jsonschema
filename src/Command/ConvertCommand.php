<?php declare(strict_types=1);

namespace HSkrasek\OpenAPI\Command;

use League\Flysystem\FilesystemInterface;
use League\Pipeline\Pipeline;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends Command
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var Pipeline
     */
    private $pipeline;

    public function __construct(FilesystemInterface $filesystem, Pipeline $pipeline)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->pipeline   = $pipeline;
    }

    protected function configure()
    {
        $this->setName('convert')
            ->setDescription('Convert OpenAPI 3 schema to JSON Schema')
            ->setHelp('This command allows you to convert a directory of OpenAPI 3 schema files to JSON Schema files')
            ->addArgument(
                'input',
                InputArgument::REQUIRED,
                'The directory containing all the OpenAPI 3 schema files'
            )
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'The directory to output the converted JSON Schema files'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $oasDirectory        = $input->getArgument('input');
        $jsonSchemaDirectory = $input->getArgument('output');

        if (!$this->filesystem->has($oasDirectory)) {
            $output->writeln("<error>OpenAPI Schema Directory does not exist ($oasDirectory)</error>");

            return -1;
        }

        if (!$this->filesystem->has($jsonSchemaDirectory)) {
            $output->writeln('<info>JSON Schema output directory does not exist, creating...</info>');
            $this->filesystem->createDir($jsonSchemaDirectory);
        }

        $convertedFiles = $this->pipeline->process([$oasDirectory, $jsonSchemaDirectory]);

        $output->writeln("<info>Successfully converted $convertedFiles schema files</info>");
    }
}
