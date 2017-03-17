<?php
namespace Adminaut\Service;


class AccessControlService
{
    const NONE = 0;
    const READ = 1;
    const WRITE = 2;
    const FULL = 3;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var \Adminaut\Entity\UserEntity
     */
    private $user;

    public function __construct($roles)
    {
        $this->roles = $roles;
    }

    public function isAllowed($module, $permissionLevel, $element = null, $entity = null) {
        if($this->user->getRole() == "admin") {
            return true;
        }

        if(!isset($this->roles[$this->user->getRole()])) {
            return false;
        }

        $role = $this->roles[$this->user->getRole()];

        $allowed = false;

        if(!isset($role['modules'][$module])) {
            return false;
        }

        if($role['modules'][$module]['global'] >= $permissionLevel) {
            $allowed = true;
        }

        if($element && isset($role['modules'][$module]['elements'][$element])) {
            if($role['modules'][$module]['elements'][$element]['permission'] > $role['modules'][$module]['global']) {
                throw new \Exception("You cannot set element permission bigger than global permission.");
            } else {
                if($role['modules'][$module]['elements'][$element] >= $permissionLevel) {
                    $allowed = true;
                } else {
                    $allowed = false;
                }
            }
        }

        return $allowed;
    }

    /**
     * @return \Adminaut\Entity\UserEntity|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Adminaut\Entity\UserEntity $user
     */
    public function setUser(\Adminaut\Entity\UserEntity $user)
    {
        $this->user = $user;
    }
}