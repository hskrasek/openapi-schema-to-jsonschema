<?php declare(strict_types=1);

namespace HSkrasek\OpenAPI\Command;

use HSkrasek\OpenAPI\Converter;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ConvertCommand extends Command
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var Converter
     */
    private $converter;

    public function __construct(FilesystemInterface $filesystem, Converter $converter)
    {
        $this->filesystem = $filesystem;
        $this->converter  = $converter;

        parent::__construct();
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
            )
            ->addOption(
                'overwrite',
                'w',
                InputOption::VALUE_NONE,
                'Whether or not to overwrite existing JSON Schema files'
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

        $openApiSchemaFiles = $this->getOpenAPISchemaFiles($oasDirectory);

        if (empty($openApiSchemaFiles)) {
            $output->writeln("No OpenAPI schema files found.");

            return -1;
        }

        $jsonSchemaFiles = $this->convertOpenAPISchemaFiles($openApiSchemaFiles);

        $this->saveConvertedJsonSchema($jsonSchemaFiles, $jsonSchemaDirectory, $input->getOption('overwrite') ?? false);

        $output->writeln('<info>Successfully converted ' . count($jsonSchemaFiles) . ' </info>');
    }

    private function getOpenAPISchemaFiles(string $directory): array
    {
        return array_filter($this->filesystem->listFiles($directory), function (array $file) {
            return \in_array($file['extension'], ['json', 'yml', 'yaml'], true);
        });
    }

    private function convertOpenAPISchemaFiles(array $openApiSchemaFiles): array
    {
        $convertedSchemaFiles = [];

        foreach ($openApiSchemaFiles as $openApiSchemaFile) {
            $convertedSchemaFiles[$openApiSchemaFile['filename'] . '.json'] = $this->converter->convert(
                $this->getOpenAPISchemaContents($openApiSchemaFile)
            );
        }

        return $convertedSchemaFiles;
    }

    private function getOpenAPISchemaContents(array $openApiSchemaFile): object
    {
        if ($openApiSchemaFile['extension'] === 'json') {
            return json_decode($this->filesystem->read($openApiSchemaFile['path']));
        }

        return Yaml::parse(
            $this->filesystem->read($openApiSchemaFile['path']),
            Yaml::PARSE_OBJECT | Yaml::PARSE_OBJECT_FOR_MAP | Yaml::PARSE_DATETIME | Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE
        );
    }

    /**
     * @param array $jsonSchemaFiles
     * @param string $jsonSchemaDirectory
     *
     * @param bool $overwrite
     *
     * @throws \League\Flysystem\FileExistsException
     */
    protected function saveConvertedJsonSchema(
        array $jsonSchemaFiles,
        string $jsonSchemaDirectory,
        bool $overwrite = false
    ): void {
        foreach ($jsonSchemaFiles as $filename => $jsonSchemaFile) {
            $schemaPath = $jsonSchemaDirectory . DIRECTORY_SEPARATOR . $filename;

            if ($this->filesystem->has($schemaPath) && $overwrite) {
                $this->filesystem->put(
                    $schemaPath,
                    json_encode($jsonSchemaFile, JSON_PRETTY_PRINT)
                );

                continue;
            }

            $this->filesystem->write(
                $schemaPath,
                json_encode($jsonSchemaFile, JSON_PRETTY_PRINT)
            );
        }
    }
}
