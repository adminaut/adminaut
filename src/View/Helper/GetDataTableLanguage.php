<?php

namespace Adminaut\View\Helper;

use Adminaut\Authentication\Service\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

/**
 * Class GetDataTableLanguage
 * @package Adminaut\View\Helper
 */
class GetDataTableLanguage extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * GetDataTableLanguage constructor.
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $language = 'cs';

        if ($this->authenticationService->hasIdentity()) {
            $language = $this->authenticationService->getIdentity()->getLanguage();
        }

        switch ($language) {
            case 'en':
                return '//cdn.datatables.net/plug-ins/1.10.16/i18n/English.json';
            case 'de':
                return '//cdn.datatables.net/plug-ins/1.10.16/i18n/German.json';
            case 'sk':
                return '//cdn.datatables.net/plug-ins/1.10.16/i18n/Slovak.json';
            case 'cs':
            default:
                return '//cdn.datatables.net/plug-ins/1.10.16/i18n/Czech.json';
        }
    }
}
