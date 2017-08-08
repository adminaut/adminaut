<?php

namespace Adminaut\Options;

use League\Flysystem\Adapter\Local;
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
    private $appearance = [];

    /**
     * @var array
     */
    private $modules = [];

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @var array
     */
    private $manifest = [];

    /**
     * @var array
     */
    private $users = [];

    /**
     * @var array
     */
    private $authAdapter = [];

    /**
     * @var array
     */
    private $cookieStorage = [];

    /**
     * @var array
     */
    private $variables = [];

    /**
     * @var array
     */
    private $filesystem = [
        'private' => [
            'adapter' => Local::class,
            'options' => [
                'root' => './data/files',
            ],
        ],
        'public' => [
            'adapter' => Local::class,
            'options' => [
                'root' => './www_root/_cache/files',
                'trim' => './www_root/',
            ],
        ],
    ];

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
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param array $variables
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;
    }

    /**
     * @return array
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param array $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = array_merge($this->filesystem, $filesystem);
    }
}
