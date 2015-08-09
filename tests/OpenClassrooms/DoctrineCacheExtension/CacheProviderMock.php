<?php

namespace OpenClassrooms\Tests\DoctrineCacheExtension;

use Doctrine\Common\Cache\ArrayCache;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class CacheProviderMock extends ArrayCache
{
    const ID = 1;

    const NAMESPACE_ID = 'namespace_1';

    const NAMESPACE_ID_VALUE = 'namespace';

    const DATA = 'data';

    const NAMESPACE_DATA = 'namespace data';

    /**
     * @var bool
     */
    public $doFetchHasBeenCalled = false;

    /**
     * @var bool
     */
    public $doContainsHasBeenCalled = false;

    /**
     * @var bool
     */
    public $doSaveHasBeenCalled = false;

    /**
     * @var bool
     */
    public $doDeleteHasBeenCalled = false;

    /**
     * @var bool
     */
    public $doGetStatsHasBeenCalled = false;

    /**
     * @var bool
     */
    public $doFlushHasBeenCalled = false;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $data;

    /**
     * @var int
     */
    public $lifeTime;

    public function doFetch($id)
    {
        $this->doFetchHasBeenCalled = true;
        $this->id = $id[1];

        return parent::doFetch($id);
    }

    public function doContains($id)
    {
        $this->doContainsHasBeenCalled = true;
        $this->id = $id;

        return parent::doContains($id);
    }

    public function doSave($id, $data, $lifeTime = null)
    {
        $this->doSaveHasBeenCalled = true;
        $this->id = $id;
        $this->data = $data;
        $this->lifeTime = $lifeTime;

        return parent::doSave($id, $data, $lifeTime);
    }

    public function doDelete($id)
    {
        $this->doDeleteHasBeenCalled = true;
        $this->id = $id;

        return parent::doDelete($id);
    }

    public function doGetStats()
    {
        $this->doGetStatsHasBeenCalled = true;

        return parent::doGetStats();
    }

    protected function doFlush()
    {
        $this->doFlushHasBeenCalled = true;

        return parent::doFlush();
    }
}
