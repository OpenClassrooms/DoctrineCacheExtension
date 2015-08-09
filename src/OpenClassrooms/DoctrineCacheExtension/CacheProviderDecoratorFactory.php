<?php

namespace OpenClassrooms\DoctrineCacheExtension;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\CouchbaseCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\MongoDBCache;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\RiakCache;
use Doctrine\Common\Cache\WinCacheCache;
use Doctrine\Common\Cache\XcacheCache;
use Doctrine\Common\Cache\ZendDataCache;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CacheProviderDecoratorFactory implements CacheProviderDecoratorFactoryInterface
{
    /**
     * @var int
     */
    private static $defaultLifetime = AbstractCacheProviderDecorator::DEFAULT_LIFE_TIME;

    /**
     * @return AbstractCacheProviderDecorator
     */
    public static function create($type, ...$args)
    {
        switch ($type) {
            case 'apc':
                $cacheProvider = new ApcCache();
                break;
            case 'array':
                $cacheProvider = new ArrayCache();
                break;
            case 'couchbase':
                $cacheProvider = new CouchbaseCache();
                break;
            case 'file_system':
                $cacheProvider = new FilesystemCache(...$args);
                break;
            case 'memcache':
                $cacheProvider = new MemcacheCache();
                break;
            case 'memcached':
                $cacheProvider = new MemcachedCache();
                break;
            case 'mongodb':
                $cacheProvider = new MongoDBCache(...$args);
                break;
            case 'php_file':
                $cacheProvider = new PhpFileCache(...$args);
                break;
            case 'redis':
                $cacheProvider = new RedisCache();
                break;
            case 'riak':
                $cacheProvider = new RiakCache(...$args);
                break;
            case 'wincache':
                $cacheProvider = new WinCacheCache();
                break;
            case 'xcache':
                $cacheProvider = new XcacheCache();
                break;
            case 'zenddata':
                $cacheProvider = new ZendDataCache();
                break;
            default:
                throw new \InvalidArgumentException('Type "'.$type.'" is not supported.');
        }

        return new CacheProviderDecorator($cacheProvider, self::$defaultLifetime);
    }

    public function setDefaultLifetime($defaultLifetime)
    {
        self::$defaultLifetime = $defaultLifetime;
    }
}
