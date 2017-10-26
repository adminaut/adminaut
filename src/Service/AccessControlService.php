<?php

namespace Adminaut\Service;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Entity\UserEntity;

/**
 * Class AccessControlService
 * @package Adminaut\Service
 */
class AccessControlService
{
    /**
     * Constants.
     */
    const NONE = 0;
    const READ = 1;
    const WRITE = 2;
    const FULL = 3;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var UserEntity
     */
    private $user;

    /**
     * AccessControlService constructor.
     * @param AuthenticationService $authenticationService
     * @param array $roles
     */
    public function __construct(AuthenticationService $authenticationService, array $roles)
    {
        $this->roles = $roles;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param $module
     * @param $permissionLevel
     * @param null $element
     * @param null $entity
     * @return bool
     * @throws \Exception
     */
    public function isAllowed($module, $permissionLevel, $element = null, $entity = null)
    {
        if (true !== $this->authenticationService->hasIdentity()) {
            return false;
        }

        $this->user = $this->authenticationService->getIdentity();

        if ($this->user->getRole() == 'admin') {
            return true;
        }

        if (!isset($this->roles[$this->user->getRole()])) {
            return false;
        }

        $role = $this->roles[$this->user->getRole()];

        $allowed = false;

        if (!isset($role['modules'][$module])) {
            return false;
        }

        if ($role['modules'][$module]['global'] >= $permissionLevel) {
            $allowed = true;
        }

        if ($element && isset($role['modules'][$module]['elements'][$element])) {
            if ($role['modules'][$module]['elements'][$element]['permission'] > $role['modules'][$module]['global']) {
                throw new \Exception('You cannot set element permission bigger than global permission.');
            } else {
                if ($role['modules'][$module]['elements'][$element] >= $permissionLevel) {
                    $allowed = true;
                } else {
                    $allowed = false;
                }
            }
        }

        return $allowed;
    }
}
