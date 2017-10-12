<?php

namespace Adminaut\Options;

use Adminaut\Entity\UserEntity;
use Zend\Stdlib\AbstractOptions;

/**
 * Class UsersOptions
 * @package Adminaut\Options
 */
class UsersOptions extends AbstractOptions
{

    /**
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    private $userEntityClass = UserEntity::class;

    /**
     * @return string
     */
    public function getUserEntityClass()
    {
        return $this->userEntityClass;
    }

    /**
     * @param string $userEntityClass
     */
    public function setUserEntityClass($userEntityClass)
    {
        $this->userEntityClass = $userEntityClass;
    }
}
