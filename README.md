# Doctrine Cache Extension
[![Build Status](https://travis-ci.org/OpenClassrooms/DoctrineCacheExtension.svg?branch=master)](https://travis-ci.org/OpenClassrooms/DoctrineCacheExtension)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d595725d-9e23-4386-b0ba-444e1a118f60/mini.png)](https://insight.sensiolabs.com/projects/d595725d-9e23-4386-b0ba-444e1a118f60)
[![Coverage Status](https://coveralls.io/repos/OpenClassrooms/DoctrineCacheExtension/badge.svg?branch=master&service=github)](https://coveralls.io/github/OpenClassrooms/DoctrineCacheExtension?branch=master)

Doctrine Cache extension adds features to Doctrine Cache implementation
- Default lifetime
- Fetch with a namespace
- Save with a namespace
- Cache invalidation through namespace strategy

## Installation
The easiest way to install DoctrineCacheExtension is via [composer](http://getcomposer.org/).

Create the following `composer.json` file and run the `php composer.phar install` command to install it.

```json
{
    "require": {
        "openclassrooms/doctrine-cache-extension": "*"
    }
}
```
```php
<?php
require 'vendor/autoload.php';

use OpenClassrooms\DoctrineCacheExtension\CacheProviderDecorator;

//do things
```
<a name="install-nocomposer"/>

## Usage
### Instantiation
OpenClassrooms CacheProviderDecorator needs a Doctrine CacheProvider to be instantiate.
```php
$cacheProvider = new ArrayCache();

$cacheProviderDecorator = new CacheProviderDecorator($cacheProvider);
```

A factory can be used.
```php
// Default builder, build a cache using ArrayCache Provider
$cache = new CacheBuilderImpl()->build();

// Using a CacheProvider
$cache = new CacheBuilderImpl()
    ->withCacheProvider($redisCache)
    ->build();

// Optional default lifetime
$cache = new CacheBuilderImpl()
    ->withCacheProvider($redisCache)
    ->withDefaultLifetime(300)
    ->build();
```

### Default lifetime
```php
$cache->setDefaultLifetime(300);
$cache->save($id, $data);
```

### Fetch with namespace
```php
$data = $cache->fetchWithNamespace($id, $namespaceId);
```

### Save with namespace
```php
// Namespace and life time can be null
$data = $cache->saveWithNamespace($id, $data, $namespaceId, $lifeTime);
```

### Cache invalidation
```php
$cache->invalidate($namespaceId);
```
### CacheProvider Builder
The library provides a CacheProvider Builder

```php
// Memcache
$cacheProvider = new CacheProviderBuilderImpl()
    ->create(CacheProviderType::MEMCACHE)
    ->withHost('127.0.0.1')
    ->withPort(11211) // Default 11211
    ->withTimeout(1) // Default 1
    ->build();

// Memcached
$cacheProvider = new CacheProviderBuilderImpl()
    ->create(CacheProviderType::MEMCACHED)
    ->withHost('127.0.0.1')
    ->withPort(11211) // Default 11211
    ->build();

// Redis
$cacheProvider = new CacheProviderBuilderImpl()
    ->create(CacheProviderType::REDIS)
    ->withHost('127.0.0.1')
    ->withPort(6379) // Default 6379
    ->withTimeout(0.0) // Default 0.0
    ->build();

// Array
$cacheProvider = new CacheProviderBuilderImpl()
    ->create(CacheProviderType::ARRAY_CACHE)
    ->build();

```
