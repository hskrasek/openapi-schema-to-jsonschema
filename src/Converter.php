<?php namespace HSkrasek\OpenAPI;

class Converter
{
    private const STRUCTS = [
        'allOf',
        'anyOf',
        'oneOf',
        'not',
        'items',
        'additionalProperties',
    ];

    private const NOT_SUPPORTED = [
        'nullable',
        'discriminator',
        'readOnly',
        'writeOnly',
        'xml',
        'externalDocs',
        'example',
        'deprecated',
    ];

    /**
     * @var array
     */
    private $options;

    public function __construct(?array $options = null)
    {
        $this->options = $this->createOptions($options ?: []);
    }

    /**
     * @param mixed $schema
     *
     * @return mixed
     */
    public function convert($schema)
    {
        $schema = $this->convertSchema($schema);
        data_set($schema, '$schema', 'http://json-schema.org/draft-04/schema#');

        return $schema;
    }

    /**
     * @param mixed $schema
     *
     * @return mixed
     */
    private function convertSchema($schema)
    {
        foreach (self::STRUCTS as $i => $struct) {
            if (\is_array(data_get($schema, $struct))) {
                foreach ($schema->$struct as $j => $nestedStruct) {
                    $schema->$struct[$j] = $this->convertSchema($nestedStruct);
                }
            } elseif (\is_object(data_get($schema, $struct))) {
                $schema->$struct = $this->convertSchema($schema->$struct);
            }
        }

        if (\is_object($properties = data_get($schema, 'properties'))) {
            data_set($schema, 'properties', $this->convertProperties($properties));

            if (\is_array($required = data_get($schema, 'required'))) {
                data_set($schema, 'required', $required = $this->cleanRequired($required, $properties));

                if (\count($required) === 0) {
                    $this->removeFromSchema($schema, 'required');
                }
            }

            if (\count(get_object_vars($properties)) === 0) {
                $this->removeFromSchema($schema, 'properties');
            }
        }

        $schema = $this->convertTypes($schema);

        if (\is_object(data_get($schema, 'x-patternProperties')) && $this->options['support_pattern_properties']) {
            $schema = $this->convertPatternProperties($schema, $this->options['pattern_properties_handler']);
        }

        foreach ($this->options['not_supported'] as $notSupported) {
            $this->removeFromSchema($schema, $notSupported);
        }

        return $schema;
    }

    private function convertProperties($properties)
    {
        foreach ($properties as $key => $property) {
            $removeProperty = false;

            foreach ($this->options['remove_properties'] as $prop) {
                if (data_get($property, $prop) === true) {
                    $removeProperty = true;
                }
            }

            if ($removeProperty) {
                $this->removeFromSchema($properties, $key);
                continue;
            }

            data_set($properties, $key, $this->convertSchema($property));
        }

        return $properties;
    }

    /**
     * @param mixed $schema
     *
     * @return mixed
     */
    private function convertTypes($schema)
    {
        if (null === data_get($schema, 'type')) {
            return $schema;
        }

        if (data_get($schema, 'type') === 'string' && data_get(
            $schema,
            'format'
        ) === 'date' && $this->options['convert_date'] === true) {
            data_set($schema, 'format', 'date-time');
        }

        $newType   = null;
        $newFormat = null;

        switch (data_get($schema, 'type')) {
            case 'integer':
                $newType = 'integer';
                break;
            case 'long':
                $newType   = 'integer';
                $newFormat = 'int64';
                break;
            case 'float':
                $newType   = 'number';
                $newFormat = 'float';
                break;
            case 'double':
                $newType   = 'number';
                $newFormat = 'double';
                break;
            case 'byte':
                $newType   = 'string';
                $newFormat = 'byte';
                break;
            case 'binary':
                $newType   = 'string';
                $newFormat = 'binary';
                break;
            case 'date':
                $newType   = 'string';
                $newFormat = $this->options['convert_date'] ? 'date-time' : 'date';
                break;
            case 'dateTime':
                $newType   = 'string';
                $newFormat = 'date-time';
                break;
            case 'password':
                $newType   = 'string';
                $newFormat = 'password';
                break;
            default:
                $newType = data_get($schema, 'type');
        }

        data_set($schema, 'type', $newType);
        data_set($schema, 'format', \is_string($newFormat) ? $newFormat : data_get($schema, 'format'));

        if (null === data_get($schema, 'format')) {
            $this->removeFromSchema($schema, 'format');
        }

        if (data_get($schema, 'nullable', false) === true) {
            data_set($schema, 'type', [data_get($schema, 'type'), 'null']);
        }

        return $schema;
    }

    private function cleanRequired(?array $required = [], $properties = null): array
    {
        foreach ($required as $key => $requiredProperty) {
            if (!isset($properties->{$requiredProperty}, $properties)) {
                unset($required[$key]);
            }
        }

        return array_values($required);
    }

    private function convertPatternProperties($schema, callable $handler)
    {
        data_set($schema, 'patternProperties', data_get($schema, 'x-patternProperties'));
        $this->removeFromSchema($schema, 'x-patternProperties');

        return call_user_func($handler, $schema);
    }

    private function patternPropertiesHandler($schema)
    {
        $patternProperties = data_get($schema, 'patternProperties');

        if (!\is_object($additionalProperties = data_get($schema, 'additionalProperties'))) {
            return $schema;
        }

        foreach ($patternProperties as $patternProperty) {
            if ($patternProperty == $additionalProperties) {
                data_set($schema, 'additionalProperties', false);
                break;
            }
        }

        return $schema;
    }

    private function createOptions(array $options): array
    {
        $options['convert_date']               = $options['convert_date'] ?? false;
        $options['support_pattern_properties'] = $options['support_pattern_properties'] ?? false;
        $options['keep_not_supported']         = $options['keep_not_supported'] ?? [];
        $options['pattern_properties_handler'] = $options['pattern_properties_handler'] ?? [
                $this,
                'patternPropertiesHandler',
            ];

        $options['remove_properties'] = [];

        if ($options['remove_read_only'] ?? false) {
            $options['remove_properties'][] = 'readOnly';
        }

        if ($options['remove_write_only'] ?? false) {
            $options['remove_properties'][] = 'writeOnly';
        }

        $options['not_supported'] = $this->resolveNotSupported(self::NOT_SUPPORTED, $options['keep_not_supported']);

        return $options;
    }

    private function resolveNotSupported(array $notSupported, array $toRetain): array
    {
        return array_values(array_diff($notSupported, $toRetain));
    }

    private function removeFromSchema($schema, string $key): void
    {
        if (\is_object($schema)) {
            unset($schema->{$key});

            return;
        }

        unset($schema[$key]);

        return;
    }
}
