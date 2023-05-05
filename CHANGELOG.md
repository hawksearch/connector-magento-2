# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.6.1] - 2023-05-05
### ADDED
- Add ConnectionScopeResolver interface to resolver current configuration scope on admin system configuration pages ([#8](https://github.com/hawksearch/connector-magento-2/pull/8))
  Refs: [#HC-1363](https://bridgeline.atlassian.net/browse/HC-1363)
- 
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


[Unreleased]: https://github.com/hawksearch/connector-magento-2/compare/v2.6.1...HEAD
[2.6.1]: https://github.com/hawksearch/esindexing-magento-2/compare/v2.6.0...v2.6.1
