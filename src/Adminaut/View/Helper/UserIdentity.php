<?php

namespace Adminaut\View\Helper;

use Adminaut\Authentication\Service\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

/**
 * Class UserIdentity
 * @package Adminaut\View\Helper
 */
class UserIdentity extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * UserIdentity constructor.
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return \Adminaut\Entity\UserEntity|bool|null
     */
    public function __invoke()
    {
        if ($this->authenticationService->hasIdentity()) {
            return $this->authenticationService->getIdentity();
        }
        return false;
    }
}
