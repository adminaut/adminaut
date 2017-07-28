<?php

namespace Adminaut\Controller\Plugin;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Entity\UserEntity;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class AuthenticationPlugin
 * @package Adminaut\Controller\Plugin
 */
class AuthenticationPlugin extends AbstractPlugin
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * AuthenticationPlugin constructor.
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->authenticationService->hasIdentity();
    }

    /**
     * @return UserEntity|null
     */
    public function getIdentity()
    {
        return $this->authenticationService->getIdentity();
    }
}
