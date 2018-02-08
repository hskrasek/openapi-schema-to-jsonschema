# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## 0.2.0

### Added
- The ability to dereference schema objects, allowing for embedded schemas
- Parsers to handle parsing JSON and YAML files

### Changed
- The convert command to utilize a pipeline and stages to convert files

## 0.1.1

### Fixed
- Code style issues on the converter
- Removed object type hints as they are only in PHP 7.2, and this package supports 7.1

## 0.1.0

### Added
- A converter capable of converting OpenAPI Schema objects into JSON Schema (Draft 4) objects
- Command line tool to convert a directory of schema files, into JSON Schema files
