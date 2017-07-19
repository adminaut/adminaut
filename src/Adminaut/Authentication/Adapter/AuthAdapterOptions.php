<?php

namespace Adminaut\Authentication\Adapter;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AuthAdapterOptions
 * @package Adminaut\Authentication\Options
 */
class AuthAdapterOptions extends AbstractOptions
{
    const CONFIG_KEY = 'auth-adapter';

    /**
     * @var int
     */
    protected $failedLoginsCount = 3;

    /**
     * @var int
     */
    protected $failedLoginsTimeout = 30; // in seconds

    /**
     * @return int
     */
    public function getFailedLoginsCount()
    {
        return $this->failedLoginsCount;
    }

    /**
     * @param int $failedLoginsCount
     */
    public function setFailedLoginsCount($failedLoginsCount)
    {
        $this->failedLoginsCount = $failedLoginsCount;
    }

    /**
     * @return int
     */
    public function getFailedLoginsTimeout()
    {
        return $this->failedLoginsTimeout;
    }

    /**
     * @param int $failedLoginsTimeout
     */
    public function setFailedLoginsTimeout($failedLoginsTimeout)
    {
        $this->failedLoginsTimeout = $failedLoginsTimeout;
    }
}
