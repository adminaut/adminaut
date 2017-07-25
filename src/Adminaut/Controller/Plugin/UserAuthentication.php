<?php

namespace Adminaut\Controller\Plugin;

use Adminaut\Authentication\Service\AuthenticationService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class UserAuthentication
 * @package Adminaut\Controller\Plugin
 */
class UserAuthentication extends AbstractPlugin
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * UserAuthentication constructor.
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
     * @return \Adminaut\Entity\UserEntity|null
     */
    public function getIdentity()
    {
        return $this->authenticationService->getIdentity();
    }
}
