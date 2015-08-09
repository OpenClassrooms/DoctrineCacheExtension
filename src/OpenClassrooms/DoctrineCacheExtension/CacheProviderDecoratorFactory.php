<?php

namespace OpenClassrooms\DoctrineCacheExtension;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface CacheProviderDecoratorFactory
{
    /**
     * @return CacheProviderDecorator
     */
    public static function create($type, ...$args);

    public function setDefaultLifetime($defaultLifetime);
}
