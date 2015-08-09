<?php

namespace OpenClassrooms\Tests\DoctrineCacheExtension;

use OpenClassrooms\DoctrineCacheExtension\CacheProviderDecoratorFactory;
use OpenClassrooms\DoctrineCacheExtension\CacheProviderDecoratorFactoryImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CacheProviderDecoratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    const DIRECTORY = __DIR__.'/../tmp';

    /**
     * @var CacheProviderDecoratorFactory
     */
    private $factory;

    /**
     * @return array
     */
    public static function typeProvider()
    {
        return [
            ['apc', 'Doctrine\Common\Cache\ApcCache', []],
            ['array', 'Doctrine\Common\Cache\ArrayCache', []],
            ['couchbase', 'Doctrine\Common\Cache\CouchbaseCache', []],
            ['file_system', 'Doctrine\Common\Cache\FilesystemCache', [self::DIRECTORY]],
            ['memcache', 'Doctrine\Common\Cache\MemcacheCache', []],
            ['memcached', 'Doctrine\Common\Cache\MemcachedCache', []],
            ['mongodb', 'Doctrine\Common\Cache\MongoDBCache', [\Mockery::mock('MongoCollection')]],
            ['php_file', 'Doctrine\Common\Cache\PhpFileCache', [self::DIRECTORY]],
            ['redis', 'Doctrine\Common\Cache\RedisCache', []],
            ['riak', 'Doctrine\Common\Cache\RiakCache', [\Mockery::mock('\Riak\Bucket')]],
            ['wincache', 'Doctrine\Common\Cache\WinCacheCache', []],
            ['xcache', 'Doctrine\Common\Cache\XCacheCache', []],
            ['zenddata', 'Doctrine\Common\Cache\ZendDataCache', []],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass()
    {
        rmdir(self::DIRECTORY);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function InvalidType_ThrowException()
    {
        $this->factory->create('invalid type');
    }

    /**
     * @test
     */
    public function WithDefaultLifetime_create()
    {
        $this->factory->setDefaultLifetime(100);
        $cacheProviderDecorator = $this->factory->create('array');
        $this->assertAttributeEquals(100, 'defaultLifetime', $cacheProviderDecorator);
    }

    /**
     * @test
     * @dataProvider typeProvider
     */
    public function Create($inputType, $expectedCacheProvider, $args)
    {
        $factory = new CacheProviderDecoratorFactoryImpl();
        $actualCacheProvider = $factory->create($inputType, ...$args);
        $this->assertAttributeInstanceOf($expectedCacheProvider, 'cacheProvider', $actualCacheProvider);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->factory = new CacheProviderDecoratorFactoryImpl();
    }
}
