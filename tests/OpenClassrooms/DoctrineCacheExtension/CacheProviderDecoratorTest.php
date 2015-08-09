<?php

namespace OpenClassrooms\Tests\DoctrineCacheExtension;

use Doctrine\Common\Cache\RedisCache;
use OpenClassrooms\DoctrineCacheExtension\AbstractCacheProviderDecorator;
use OpenClassrooms\DoctrineCacheExtension\CacheProviderDecorator;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CacheProviderDecoratorTest extends \PHPUnit_Framework_TestCase
{
    const EXPECTED_ID = '[1][1]';

    const LIFE_TIME = 100;

    const EXPECTED_NAMESPACE_ID_VALUE = '['.CacheProviderMock::NAMESPACE_ID_VALUE;

    const NON_EXISTING_NAMESPACE_ID = -1;

    /**
     * @var CacheProviderMock
     */
    private $emptyCacheProvider;

    /**
     * @var AbstractCacheProviderDecorator
     */
    private $emptyCacheProviderDecorator;

    /**
     * @var CacheProviderMock
     */
    private $cacheProvider;

    /**
     * @var AbstractCacheProviderDecorator
     */
    private $cacheProviderDecorator;

    /**
     * @test
     */
    public function GetCacheProvider_ReturnCacheProvider()
    {
        $this->assertAttributeEquals($this->cacheProvider, 'cacheProvider', $this->cacheProviderDecorator);
    }

    /**
     * @test
     */
    public function DoFetch()
    {
        $this->assertEquals(CacheProviderMock::DATA, $this->cacheProviderDecorator->fetch(CacheProviderMock::ID));
        $this->assertTrue($this->cacheProvider->doFetchHasBeenCalled);
        $this->assertEquals(self::EXPECTED_ID, $this->cacheProvider->id);
    }

    /**
     * @test
     */
    public function DoContains()
    {
        $this->assertTrue($this->cacheProviderDecorator->contains(CacheProviderMock::ID));
        $this->assertTrue($this->cacheProvider->doContainsHasBeenCalled);
        $this->assertEquals(self::EXPECTED_ID, $this->cacheProvider->id);
    }

    /**
     * @test
     */
    public function DoSave()
    {
        $this->assertTrue($this->emptyCacheProviderDecorator->save(CacheProviderMock::ID, CacheProviderMock::DATA));
        $this->assertTrue($this->emptyCacheProvider->doSaveHasBeenCalled);
        $this->assertEquals(self::EXPECTED_ID, $this->emptyCacheProvider->id);
        $this->assertEquals(CacheProviderMock::DATA, $this->emptyCacheProvider->data);
    }

    /**
     * @test
     */
    public function DoDelete()
    {
        $this->assertTrue($this->cacheProviderDecorator->delete(CacheProviderMock::ID));
        $this->assertTrue($this->cacheProvider->doDeleteHasBeenCalled);
        $this->assertEquals(self::EXPECTED_ID, $this->cacheProvider->id);
    }

    /**
     * @test
     */
    public function DoFlush()
    {
        $this->assertTrue($this->cacheProviderDecorator->flushAll());
        $this->assertTrue($this->cacheProvider->doFlushHasBeenCalled);
    }

    /**
     * @test
     */
    public function doGetStats()
    {
        $this->assertNull($this->cacheProviderDecorator->getStats());
        $this->assertTrue($this->cacheProvider->doGetStatsHasBeenCalled);
    }

    /**
     * @test
     */
    public function call()
    {
        /** @var RedisCache $cacheProviderDecorator */
        $cacheProviderDecorator = new CacheProviderDecorator(new RedisCache());
        $this->assertNull($cacheProviderDecorator->getRedis());
    }

    /**
     * @test
     */
    public function WithoutLifeTime_Save_SaveWithDefaultLifeTime()
    {
        $this->assertTrue($this->emptyCacheProviderDecorator->save(CacheProviderMock::ID, CacheProviderMock::DATA));
        $this->assertTrue($this->emptyCacheProvider->doSaveHasBeenCalled);
        $this->assertEquals(self::EXPECTED_ID, $this->emptyCacheProvider->id);
        $this->assertEquals(CacheProviderMock::DATA, $this->emptyCacheProvider->data);
        $this->assertEquals(AbstractCacheProviderDecorator::DEFAULT_LIFE_TIME, $this->emptyCacheProvider->lifeTime);
    }

    /**
     * @test
     */
    public function Save_SaveWithLifeTime()
    {
        $this->assertTrue(
            $this->emptyCacheProviderDecorator->save(CacheProviderMock::ID, CacheProviderMock::DATA, self::LIFE_TIME)
        );
        $this->assertTrue($this->emptyCacheProvider->doSaveHasBeenCalled);
        $this->assertEquals(self::EXPECTED_ID, $this->emptyCacheProvider->id);
        $this->assertEquals(CacheProviderMock::DATA, $this->emptyCacheProvider->data);
        $this->assertEquals(self::LIFE_TIME, $this->emptyCacheProvider->lifeTime);
    }

    /**
     * @test
     */
    public function WithoutNamespaceId_SaveWithNamespace()
    {
        $this->assertTrue(
            $this->emptyCacheProviderDecorator->saveWithNamespace(CacheProviderMock::ID, CacheProviderMock::DATA)
        );
        $this->assertTrue($this->emptyCacheProvider->doSaveHasBeenCalled);
        $this->assertEquals(self::EXPECTED_ID, $this->emptyCacheProvider->id);
        $this->assertEquals(CacheProviderMock::DATA, $this->emptyCacheProvider->data);
        $this->assertEquals(AbstractCacheProviderDecorator::DEFAULT_LIFE_TIME, $this->emptyCacheProvider->lifeTime);
    }

    /**
     * @test
     */
    public function SaveWithNamespace()
    {
        $this->assertTrue(
            $this->emptyCacheProviderDecorator->saveWithNamespace(
                CacheProviderMock::ID,
                CacheProviderMock::DATA,
                CacheProviderMock::NAMESPACE_ID,
                self::LIFE_TIME
            )
        );

        $this->assertTrue($this->emptyCacheProvider->doSaveHasBeenCalled);
        $this->assertStringStartsWith(self::EXPECTED_NAMESPACE_ID_VALUE, $this->emptyCacheProvider->id);
        $this->assertEquals(CacheProviderMock::DATA, $this->emptyCacheProvider->data);
        $this->assertEquals(self::LIFE_TIME, $this->emptyCacheProvider->lifeTime);
    }

    /**
     * @test
     */
    public function WithoutNamespaceId_FetchWithNamespace_ReturnData()
    {
        $data = $this->cacheProviderDecorator->fetchWithNamespace(CacheProviderMock::ID);

        $this->assertEquals(CacheProviderMock::DATA, $data);
        $this->assertTrue($this->cacheProvider->doFetchHasBeenCalled);
        $this->assertEquals(self::EXPECTED_ID, $this->cacheProvider->id);
    }

    /**
     * @test
     */
    public function WithNamespaceId_FetchWithNamespace_ReturnData()
    {
        $this->emptyCacheProvider->save(CacheProviderMock::NAMESPACE_ID, CacheProviderMock::NAMESPACE_ID_VALUE);
        $this->emptyCacheProvider->save(
            CacheProviderMock::NAMESPACE_ID_VALUE.CacheProviderMock::ID,
            CacheProviderMock::NAMESPACE_DATA
        );

        $data = $this->emptyCacheProviderDecorator->fetchWithNamespace(
            CacheProviderMock::ID,
            CacheProviderMock::NAMESPACE_ID
        );

        $this->assertEquals(CacheProviderMock::NAMESPACE_DATA, $data);
        $this->assertTrue($this->emptyCacheProvider->doFetchHasBeenCalled);
        $this->assertStringStartsWith(
            '['.CacheProviderMock::NAMESPACE_ID_VALUE.CacheProviderMock::ID.']',
            $this->emptyCacheProvider->id
        );
    }

    /**
     * @test
     */
    public function NonExistingNamespace_Invalidate_ReturnFalse()
    {
        $this->assertFalse($this->emptyCacheProviderDecorator->invalidate(self::NON_EXISTING_NAMESPACE_ID));
    }

    /**
     * @test
     */
    public function Invalidate_ReturnTrue()
    {
        $this->emptyCacheProvider->save(CacheProviderMock::NAMESPACE_ID, CacheProviderMock::NAMESPACE_ID_VALUE);
        $this->emptyCacheProvider->save(
            CacheProviderMock::NAMESPACE_ID_VALUE.CacheProviderMock::ID,
            CacheProviderMock::NAMESPACE_DATA
        );
        $invalidated = $this->emptyCacheProviderDecorator->invalidate(CacheProviderMock::NAMESPACE_ID);

        $this->assertTrue($invalidated);
        $this->assertTrue($this->emptyCacheProvider->doSaveHasBeenCalled);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->emptyCacheProvider = new CacheProviderMock();
        $this->emptyCacheProviderDecorator = new CacheProviderDecorator($this->emptyCacheProvider);
        $this->cacheProvider = new CacheProviderMock();
        $this->cacheProvider->save(CacheProviderMock::ID, CacheProviderMock::DATA);
        $this->cacheProviderDecorator = new CacheProviderDecorator($this->cacheProvider);
    }
}
