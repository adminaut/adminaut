<?php

namespace Adminaut\Authentication\Storage;

use Zend\Stdlib\AbstractOptions;

/**
 * Class CookieStorageOptions
 * @package Adminaut\Authentication\Storage
 */
class CookieStorageOptions extends AbstractOptions
{
    const CONFIG_KEY = 'cookie-storage';

    /**
     * @var string
     */
    protected $cookieName = 'access-token';

    /**
     * @var string
     */
    protected $cookiePath = '/';

    /**
     * @var bool
     */
    protected $cookieSecure = true;

    /**
     * @var bool
     */
    protected $cookieHttpOnly = true;

    /**
     * @return string
     */
    public function getCookieName()
    {
        return $this->cookieName;
    }

    /**
     * @param string $cookieName
     */
    public function setCookieName($cookieName)
    {
        $this->cookieName = $cookieName;
    }

    /**
     * @return string
     */
    public function getCookiePath()
    {
        return $this->cookiePath;
    }

    /**
     * @param string $cookiePath
     */
    public function setCookiePath($cookiePath)
    {
        $this->cookiePath = $cookiePath;
    }

    /**
     * @return bool
     */
    public function isCookieSecure()
    {
        return $this->cookieSecure;
    }

    /**
     * @param bool $cookieSecure
     */
    public function setCookieSecure($cookieSecure)
    {
        $this->cookieSecure = $cookieSecure;
    }

    /**
     * @return bool
     */
    public function isCookieHttpOnly()
    {
        return $this->cookieHttpOnly;
    }

    /**
     * @param bool $cookieHttpOnly
     */
    public function setCookieHttpOnly($cookieHttpOnly)
    {
        $this->cookieHttpOnly = $cookieHttpOnly;
    }
}
