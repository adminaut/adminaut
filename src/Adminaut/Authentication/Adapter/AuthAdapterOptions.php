<?php

namespace Adminaut\Authentication\Adapter;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AuthAdapterOptions
 * @package Adminaut\Authentication\Adapter
 */
class AuthAdapterOptions extends AbstractOptions
{
    /**
     * Constants
     */
    const CONFIG_KEY = 'auth-adapter';

    /**
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var int
     */
    protected $failedAttemptsToLock = 3;

    /**
     * @var int In seconds
     */
    protected $secondsToUnlock = 30;

    /**
     * @var bool
     */
    protected $automaticUnlockDisabled = false;

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
        $this->failedAttemptsToLock = (int)$failedAttemptsToLock;
    }

    /**
     * @return int
     */
    public function getSecondsToUnlock()
    {
        return $this->secondsToUnlock;
    }

    /**
     * @param int $secondsToUnlock
     */
    public function setSecondsToUnlock($secondsToUnlock)
    {
        $this->secondsToUnlock = (int)$secondsToUnlock;
    }

    /**
     * @return bool
     */
    public function isAutomaticUnlockDisabled()
    {
        return $this->automaticUnlockDisabled;
    }

    /**
     * @param bool $automaticUnlockDisabled
     */
    public function setAutomaticUnlockDisabled($automaticUnlockDisabled)
    {
        $this->automaticUnlockDisabled = (bool)$automaticUnlockDisabled;
    }
}
