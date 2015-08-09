<?php

namespace OpenClassrooms\DoctrineCacheExtension;

use Doctrine\Common\Cache\CacheProvider;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class CacheProviderDecorator extends CacheProvider
{
    const DEFAULT_LIFE_TIME = 0;

    /**
     * @var CacheProvider
     */
    protected $cacheProvider;

    /**
     * @var int
     */
    protected $defaultLifetime;

    public function __construct(CacheProvider $cacheProvider, $defaultLifetime = self::DEFAULT_LIFE_TIME)
    {
        $this->cacheProvider = $cacheProvider;
        $this->defaultLifetime = $defaultLifetime;
    }

    /**
     * Fetches an entry from the cache.
     *
     * @param string $id          The id of the cache entry to fetch.
     * @param string $namespaceId The id of the namespace entry to fetch.
     *
     * @return mixed The cached data or FALSE, if no cache entry exists for the given namespace and id.
     */
    public function fetchWithNamespace($id, $namespaceId = null)
    {
        if (null !== $namespaceId) {
            $namespace = $this->fetch($namespaceId);
            $id = $namespace.$id;
        }

        return $this->fetch($id);
    }

    /**
     * Invalidate a namespace.
     *
     * @param string $namespaceId The id of the namespace to invalidate.
     *
     * @return bool TRUE if the namespace entry was successfully regenerated, FALSE otherwise.
     */
    public function invalidate($namespaceId)
    {
        $namespace = $this->fetch($namespaceId);

        if (false === $namespace) {
            return false;
        }

        $newNamespace = rand(0, 10000);
        // @codeCoverageIgnoreStart
        while ($namespace === $newNamespace) {
            $newNamespace = rand(0, 10000);
        }

        // @codeCoverageIgnoreEnd
        return $this->save($namespaceId, $namespaceId.'_'.$newNamespace.'_', 0);
    }

    /**
     * Puts data into the cache.
     *
     * @param string $id       The cache id.
     * @param mixed  $data     The cache entry/data.
     * @param int    $lifeTime The cache lifetime.
     *                         If != 0, sets a specific lifetime for this cache entry (0 => infinite lifeTime).
     *
     * @return bool TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    public function save($id, $data, $lifeTime = null)
    {
        if (null === $lifeTime) {
            $lifeTime = $this->defaultLifetime;
        }

        return $this->cacheProvider->save($id, $data, $lifeTime);
    }

    /**
     * Puts data into the cache.
     *
     * @param string $id          The cache id.
     * @param mixed  $data        The cache entry/data.
     * @param string $namespaceId The namespace id.
     * @param int    $lifeTime    The cache lifetime.
     *                            If != 0, sets a specific lifetime for this cache entry (0 => infinite lifeTime).
     *
     * @return bool TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    public function saveWithNamespace($id, $data, $namespaceId = null, $lifeTime = null)
    {
        if (null !== $namespaceId) {
            $namespace = $this->fetch($namespaceId);
            if (!$namespace) {
                $namespace = $namespaceId.'_'.rand(0, 10000);
                $this->save($namespaceId, $namespace, 0);
            }
            $id = $namespace.$id;
        }

        return $this->save($id, $data, $lifeTime);
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $arguments)
    {
        return $this->cacheProvider->$name($arguments);
    }

    /**
     * @inheritdoc
     */
    protected function doFetch($id)
    {
        return $this->cacheProvider->doFetch($id);
    }

    /**
     * @inheritdoc
     */
    protected function doContains($id)
    {
        return $this->cacheProvider->doContains($id);
    }

    /**
     * @inheritdoc
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        return $this->cacheProvider->doSave($id, $data, $lifeTime);
    }

    /**
     * @inheritdoc
     */
    protected function doDelete($id)
    {
        return $this->cacheProvider->doDelete($id);
    }

    /**
     * @inheritdoc
     */
    protected function doFlush()
    {
        return $this->cacheProvider->doFlush();
    }

    /**
     * @inheritdoc
     */
    protected function doGetStats()
    {
        return $this->cacheProvider->doGetStats();
    }
}
