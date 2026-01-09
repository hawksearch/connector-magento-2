# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.11.1] - 2025-12-23
* fix: implicitly nullable parameter deprecation in [(#50)](https://github.com/hawksearch/connector-magento-2/pull/50)
* fix: add php 8.4 compatibility in [(#52)](https://github.com/hawksearch/connector-magento-2/pull/52)

## [2.11.0] - 2025-05-06
* feat: declare public API contracts ([#29](https://github.com/hawksearch/connector-magento-2/pull/29))
* feat: add type hints to method arguments ([#30](https://github.com/hawksearch/connector-magento-2/pull/30))
* fix: implement InstructionManagerPoolInterface in di ([#31](https://github.com/hawksearch/connector-magento-2/pull/31))
* ci: static code analysers and commit lint ([#32](https://github.com/hawksearch/connector-magento-2/pull/32))
* feat(Compatibility): add PublicPropertyDeprecationTrait ([#33](https://github.com/hawksearch/connector-magento-2/pull/33))
* fix(Compatibility): support classes derived from DataObject ([#34](https://github.com/hawksearch/connector-magento-2/pull/34))
* chore: update PhpDoc annotations ([#35](https://github.com/hawksearch/connector-magento-2/pull/35))
* feat: add types to private/final/internal/not API properties ([#36](https://github.com/hawksearch/connector-magento-2/pull/36))
* fix(Logger): init fileName via constructor injection ([#37](https://github.com/hawksearch/connector-magento-2/pull/37))
* feat: add return types on internal/final/private methods and annotations on public ([#38](https://github.com/hawksearch/connector-magento-2/pull/38))
* docs: add github pull request template ([#41](https://github.com/hawksearch/connector-magento-2/pull/41))
* feat: add support for PHP 8.3 ([#40](https://github.com/hawksearch/connector-magento-2/pull/40))
* feat: drop min required version to PHP 8.1, Magento 2.4.4 ([#42](https://github.com/hawksearch/connector-magento-2/pull/42))
* feat: add ArrayToJson converter for Http client ([#44](https://github.com/hawksearch/connector-magento-2/pull/44))
* refactor: add missing strict types declaration ([#45](https://github.com/hawksearch/connector-magento-2/pull/45))
* fix: exception: each method must have a doc block ([#46](https://github.com/hawksearch/connector-magento-2/pull/46))
* chore: add phpDoc for methods throwing exceptions explicitly ([#47](https://github.com/hawksearch/connector-magento-2/pull/47))
* feat: add wrapper for symfony `patch-type-declarations script` ([#43](https://github.com/hawksearch/connector-magento-2/pull/43))

### API CHANGES
#### Interfaces
The following interfaces in `\HawkSearch\Connector` namespace are defined as `@api`:
- Gateway\Config\ApiConfigInterface
- Gateway\Http\ConverterInterface
- Gateway\Http\Uri\UriBuilderInterface
- Gateway\Http\Instruction\InstructionManagerInterface
- Gateway\Http\Instruction\InstructionManagerPoolInterface

#### Classes
The following classes in `\HawkSearch\Connector` namespace are defined as `@api`:
- Block\System\Config\Logger\LogeLevels
- Gateway\Helper\HttpResponseReader
- Gateway\Helper\SubjectReader
- Gateway\Http\Converter\ArrayToJson
- Gateway\Http\Converter\JsonToArray
- Gateway\Http\Instruction\InstructionManager
- Gateway\Http\Instruction\InstructionManagerPool
- Gateway\Instruction\Result\ArrayResult
- Gateway\Instruction\Result\DefaultResult
- Gateway\Request\BuilderComposite
- Gateway\Request\StrictDataBuilder
- Gateway\Response\HandlerChain
- Gateway\Validator\ValidatorComposite
- Helper\Url


## [2.10.0] - 2024-07-02
### FEATURES
* feat: add deprecation message utility classes  ([#22](https://github.com/hawksearch/connector-magento-2/pull/22), [#23](https://github.com/hawksearch/connector-magento-2/pull/23))

### FIXES
* fix: debug message display order in Http/Client class ([072198d](https://github.com/hawksearch/connector-magento-2/pull/27/commits/072198db7a2cf73560427429e5093b56627c7bf8))

## [2.9.0] - 2024-05-28
### FEATURES
* __feat: add deprecation message utility classses__ ([#22](https://github.com/hawksearch/connector-magento-2/pull/22)), ([#23](https://github.com/hawksearch/connector-magento-2/pull/23))

### FIXES
* __fix: improve api client request logging row__ ([f4c7b6d](https://github.com/hawksearch/connector-magento-2/commit/f4c7b6d86ed42b58cf1680fc9d91c7381d3d1060))

## [2.8.0] - 2024-01-18
### FEATURES
* __feat: update ConfigProvider default params and method visibility__ ([#20](https://github.com/hawksearch/connector-magento-2/pull/20))

  Refs: HC-1449

### FIXES
* __fix: add missing ACL for Connection Settings section__ ([#19](https://github.com/hawksearch/connector-magento-2/pull/19))

  Refs: HC-1357

## [2.7.0] - 2023-07-07
### FEATURES
* update version constraints in composer.json to support PHP 8.2 and Magento 2.4.6 ([6e49905](https://github.com/hawksearch/connector-magento-2/commit/6e49905a5cc64a24810ee425913e5c8fbe34f1ac))

### FIXES
* use Laminas\Http\Client to support Magento < 2.4.6 ([5c7a451](https://github.com/hawksearch/connector-magento-2/commit/5c7a451f0f0b8d4b9e036bb68832585874264774))
  Replace Magento\Framework\HTTP\LaminasClient with Laminas\Http\Client
* replace Zend_HTTP with laminas-http ([5bf4d7d](https://github.com/hawksearch/connector-magento-2/commit/5bf4d7d14ee563e9bf329bed5dd287c26a73d406))
  Zend framework (ZF1) components that have reached end of life have been removed from the codebase

## [2.6.2] - 2023-06-16
### FEATURES
- feat: improve log handling ([8e58fa1](https://github.com/hawksearch/connector/commit/8e58fa1ecc69773dd7b74d7d4c64f10d5a52ec0a))
    - Combine all Hawksearch log records into single file
    /var/log/hawksearch_debug.log
    - Add Log Level configuration setting to control an amount of data
      is written to log files
    - Use StreamHandler to avoid memory limit issues
    - cherry-pick commit ([33dd922](https://github.com/hawksearch/connector/commit/33dd9227f90097e8c6e5a0f09dbd99cdadce8fed))
    - Refs: [#HC-1437](https://bridgeline.atlassian.net/browse/HC-1437)

### FIXES
- fix: type error when logging response ([d35e67b](https://github.com/hawksearch/connector/commit/d35e67b93ac52dad93516914386289926c1f93d5))
    - cherry-pick commit ([1ed363a](https://github.com/hawksearch/connector/commit/1ed363aa39d401bee301c9e420cea4a53f121bf8))
    - Refs:[#HC-1437](https://bridgeline.atlassian.net/browse/HC-1437)

## [2.6.1] - 2023-05-05
### ADDED
- Add ConnectionScopeResolver interface to resolver current configuration scope on admin system configuration pages ([#8](https://github.com/hawksearch/connector-magento-2/pull/8))
  Refs: [#HC-1363](https://bridgeline.atlassian.net/browse/HC-1363)

### UPDATED
- Update DataObjectHelper::camelCaseToSnakeCase method visibility from protected to public ([#9](https://github.com/hawksearch/connector-magento-2/pull/9))
  Refs: [#HC-1213](https://bridgeline.atlassian.net/browse/HC-1213)

### FIXED
- Fix reference to a non-existent deprecated function getApiUrl() ([be58c90](https://github.com/hawksearch/connector-magento-2/commit/be58c90c0a26d661ea99f88342220464a3d6ffa0)),
  Refs: [#HC-1390](https://bridgeline.atlassian.net/browse/HC-1390)

### REMOVED
- Remove setting dependency on Hawksearch Environment setting ([#8](https://github.com/hawksearch/connector-magento-2/pull/8))
  Refs: [#HC-1363](https://bridgeline.atlassian.net/browse/HC-1363)

## 2.6.0
### UPDATES
- Update URL configuration references, default Hawksearch URLs after migration to AWS (#HC-1312)

### Fixes
- Fix traling slash issue in builcding API urls (#HC-1279)


[Unreleased]: https://github.com/hawksearch/connector-magento-2/compare/v2.11.1...2.12
[2.11.1]: https://github.com/hawksearch/connector-magento-2/compare/v2.11.0...v2.11.1
[2.11.0]: https://github.com/hawksearch/connector-magento-2/compare/v2.10.0...v2.11.0
[2.10.0]: https://github.com/hawksearch/connector-magento-2/compare/v2.9.0...v2.10.0
[2.9.0]: https://github.com/hawksearch/connector-magento-2/compare/v2.8.0...v2.9.0
[2.8.0]: https://github.com/hawksearch/connector-magento-2/compare/v2.7.0...v2.8.0
[2.7.0]: https://github.com/hawksearch/connector-magento-2/compare/v2.6.2...v2.7.0
[2.6.2]: https://github.com/hawksearch/connector-magento-2/compare/v2.6.1...v2.6.2
[2.6.1]: https://github.com/hawksearch/connector-magento-2/compare/v2.6.0...v2.6.1
