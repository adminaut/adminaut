<?php
namespace Adminaut\Db\Adapter;

/**
 * Interface MasterSlaveAdapterInterface
 * @package Adminaut\Db\Adapter
 */
interface MasterSlaveAdapterInterface
{
    /**
     * @return mixed
     */
    public function getSlaveAdapter();
}