<?php

namespace Adminaut\Authentication\Storage;

use DateTime;
use Traversable;
use Zend\Stdlib\AbstractOptions;

/**
 * Class CookieStorageOptions
 * @package Adminaut\Authentication\Storage
 */
class CookieStorageOptions extends AbstractOptions
{

    /**
     * Constants
     */
    const CONFIG_KEY = 'cookie-storage';

    /**
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $cookieName = 'access-token';

    /**
     * @var string
     */
    protected $cookiePath = '/';

    /**
     * @var string
     */
    protected $cookieDomain = '';

    /**
     * @var string|DateTime
     */
    protected $cookieExpires;

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
        $this->cookieName = (string)$cookieName;
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
        $this->cookiePath = (string)$cookiePath;
    }

    /**
     * @return string
     */
    public function getCookieDomain()
    {
        return $this->cookieDomain;
    }

    /**
     * @param string $cookieDomain
     */
    public function setCookieDomain($cookieDomain)
    {
        $this->cookieDomain = (string)$cookieDomain;
    }

    /**
     * @return string|DateTime
     */
    public function getCookieExpires()
    {
        return $this->cookieExpires;
    }

    /**
     * @param string|DateTime $cookieExpires
     */
    public function setCookieExpires($cookieExpires)
    {
        $this->cookieExpires = $cookieExpires;
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
        $this->cookieSecure = (bool)$cookieSecure;
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
        $this->cookieHttpOnly = (bool)$cookieHttpOnly;
    }

    /**
     * CookieStorageOptions constructor.
     * @param array|Traversable|null $options
     */
    public function __construct($options = null)
    {
        // default cookieExpires value
        $this->cookieExpires = new DateTime('+1 Month');

        parent::__construct($options);
    }
}
