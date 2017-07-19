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
    protected $failedAttemptsToLock = 3;

    /**
     * int in seconds, null for no automatic unlock
     * @var int
     */
    protected $unlockAfter = 30;

    /**
     * @return int
     */
    public function getFailedAttemptsToLock()
    {
        return $this->failedAttemptsToLock;
    }

    /**
     * @param int $failedAttemptsToLock
     */
    public function setFailedAttemptsToLock($failedAttemptsToLock)
    {
        $this->failedAttemptsToLock = $failedAttemptsToLock;
    }

    /**
     * @return int
     */
    public function getUnlockAfter()
    {
        return $this->unlockAfter;
    }

    /**
     * @param int $unlockAfter
     */
    public function setUnlockAfter($unlockAfter)
    {
        $this->unlockAfter = $unlockAfter;
    }
}
