<?php

namespace Adminaut\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AdminautOptions
 * @package Adminaut\Options
 */
class AdminautOptions extends AbstractOptions
{
    /**
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var array
     */
    private $appearance = []; // Leave empty

    /**
     * @var array
     */
    private $modules = []; // Leave empty

    /**
     * @var array
     */
    private $roles = []; // Leave empty

    /**
     * @var array
     */
    private $manifest = []; // Leave empty

    /**
     * @var array
     */
    private $users = []; // Leave empty

    /**
     * @var array
     */
    private $authAdapter = []; // Leave empty

    /**
     * @var array
     */
    private $cookieStorage = []; // Leave empty

    /**
     * @var string
     */
    private $googleAnalytics = '';

    /**
     * @var string
     */
    private $googleAPI = '';

    /**
     * @var string
     */
    private $environment = 'production';

    //-------------------------------------------------------------------------

    /**
     * @return array
     */
    public function getAppearance()
    {
        return $this->appearance;
    }

    /**
     * @param array $appearance
     */
    public function setAppearance($appearance)
    {
        $this->appearance = $appearance;
    }

    /**
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param array $modules
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getManifest()
    {
        return $this->manifest;
    }

    /**
     * @param array $manifest
     */
    public function setManifest($manifest)
    {
        $this->manifest = $manifest;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param array $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return array
     */
    public function getAuthAdapter()
    {
        return $this->authAdapter;
    }

    /**
     * @param array $authAdapter
     */
    public function setAuthAdapter($authAdapter)
    {
        $this->authAdapter = $authAdapter;
    }

    /**
     * @return array
     */
    public function getCookieStorage()
    {
        return $this->cookieStorage;
    }

    /**
     * @param array $cookieStorage
     */
    public function setCookieStorage($cookieStorage)
    {
        $this->cookieStorage = $cookieStorage;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return string
     */
    public function getGoogleAnalytics()
    {
        return $this->googleAnalytics;
    }

    /**
     * @param string $googleAnalytics
     */
    public function setGoogleAnalytics($googleAnalytics)
    {
        $this->googleAnalytics = $googleAnalytics;
    }

    /**
     * @return string
     */
    public function getGoogleAPI()
    {
        return $this->googleAPI;
    }

    /**
     * @param string $googleAPI
     */
    public function setGoogleAPI($googleAPI)
    {
        $this->googleAPI = $googleAPI;
    }
}
