# Doctrine Cache Extension
[![Build Status](https://travis-ci.org/OpenClassrooms/DoctrineCacheExtension.svg?branch=master)](https://travis-ci.org/OpenClassrooms/DoctrineCacheExtension)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d595725d-9e23-4386-b0ba-444e1a118f60/mini.png)](https://insight.sensiolabs.com/projects/d595725d-9e23-4386-b0ba-444e1a118f60)
[![Coverage Status](https://coveralls.io/repos/OpenClassrooms/DoctrineCacheExtension/badge.svg?branch=master&service=github)](https://coveralls.io/github/OpenClassrooms/DoctrineCacheExtension?branch=master)

The Doctrine Cache extension adds the following features to Doctrine Cache implementation:
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
OpenClassrooms CacheProviderDecorator needs a Doctrine CacheProvider to be instantiated.
```php
$cacheProvider = new ArrayCache();
$cacheProviderDecorator = new CacheProviderDecorator($cacheProvider);
```

A factory can be used to accomplish this.
```php
$factory = new CacheProviderDecoratorFactory();
$cacheProvider = $factory->create('array');
```

### Default lifetime
Specify lifetime in the constructor:
```php
$cacheProviderDecorator = new CacheProviderDecorator($cacheProvider, 100);
$cacheProviderDecorator->save($id, $data);
```
Or via the factory:
```php
$cacheProvider = $factory->create('array', 100);
```
Or specify a default lifetime for all the cache providers:
```php
$factory = new CacheProviderDecoratorFactory();
$factory->setDefaultLifetime(100);
```
### Fetch with namespace
```php
$data = $cacheProviderDecorator->fetchWithNamespace($id, $namespaceId);
```

### Save with namespace
```php
// Namespace and life time can be null
$data = $cacheProviderDecorator->saveWithNamespace($id, $data, $namespaceId, $lifeTime);
```

### Cache invalidation
```php
$cacheProviderDecorator->invalidate($namespaceId);
```
