# OpenAPI Schema to JSON Schema Converter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is PHP port of the [Node package](https://github.com/mikunn/openapi-schema-to-json-schema) by the same name, so huge props to Github user [mikunn](https://github.com/mikunn) for the work he did.
This package currently converts from [OpenAPI 3.0](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md) to [JSON Schema Draft 4](http://json-schema.org/specification-links.html#draft-4).

## Features

* converts OpenAPI 3.0 Schema Object to JSON Schema Draft 4
* converts [common named data types](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md#data-types) to `type` and `format`
  * for example `type: "dateTime"` becomes `type: "string"` with `format: "date-time"`
* deletes `nullable` and adds `"null"` to `type` array if `nullable` is `true`
* supports deep structures with nested `allOf`s etc.
* removes [OpenAPI specific properties](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md#fixed-fields-20) such as `discriminator`, `deprecated` etc. unless specified otherwise
* optionally supports `patternProperties` with `x-patternProperties` in the Schema Object

**NOTE**: `$ref`s are not dereferenced. Use a dereferencer such as [json-schema-ref-parser](https://www.npmjs.com/package/json-schema-ref-parser) prior to using this package.

## Install

Via Composer

``` bash
$ composer require hskrasek/openapi-schema-to-json-schema
```

## Usage

``` bash
vendor/bin/oas-to-jsonschema convert docs/schemas docs/specs
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hunterskrasek@me.com instead of using the issue tracker.

## Credits

- [Hunter Skrasek][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/hskrasek/openapi-schema-to-json-schema.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/hskrasek/openapi-schema-to-jsonschema/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/hskrasek/openapi-schema-to-jsonschema.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/hskrasek/openapi-schema-to-jsonschema.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/hskrasek/openapi-schema-to-jsonschema.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/hskrasek/openapi-schema-to-json-schema
[link-travis]: https://travis-ci.org/hskrasek/openapi-schema-to-jsonschema
[link-scrutinizer]: https://scrutinizer-ci.com/g/hskrasek/openapi-schema-to-jsonschema/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/hskrasek/openapi-schema-to-jsonschema
[link-downloads]: https://packagist.org/packages/hskrasek/openapi-schema-to-jsonschema
[link-author]: https://github.com/hskrasek
[link-contributors]: ../../contributors
