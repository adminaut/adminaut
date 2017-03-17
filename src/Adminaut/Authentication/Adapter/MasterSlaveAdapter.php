<?php
namespace Adminaut\Db\Adapter;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform;
use Zend\Db\ResultSet;

/**
 * Class MasterSlaveAdapter
 * @package Adminaut\Db\Adapter
 */
class MasterSlaveAdapter extends Adapter implements MasterSlaveAdapterInterface
{
    /**
     * slave adapter
     *
     * @var Adapter
     */
    protected $slaveAdapter;

    /**
     * MasterSlaveAdapter constructor.
     * @param Adapter $slaveAdapter
     * @param Platform\PlatformInterface $driver
     * @param Platform\PlatformInterface|null $platform
     * @param ResultSet\ResultSetInterface|null $queryResultPrototype
     */
    public function __construct(Adapter $slaveAdapter, $driver, Platform\PlatformInterface $platform = null, ResultSet\ResultSetInterface $queryResultPrototype = null)
    {
        $this->slaveAdapter = $slaveAdapter;
        parent::__construct($driver, $platform, $queryResultPrototype);
    }

    /**
     * @return Adapter
     */
    public function getSlaveAdapter()
    {
        return $this->slaveAdapter;
    }
}