<?php

namespace OpenClassrooms\DoctrineCacheExtension;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CacheProviderDecoratorFactoryInterface
{
    /**
     * @return AbstractCacheProviderDecorator
     */
    public static function create($type, ...$args);

    public function setDefaultLifetime($defaultLifetime);
}
