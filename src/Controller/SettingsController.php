<?php

// todo: Refactor this controller so users can decide, if they want use database for settings, or config array.

namespace Adminaut\Controller;

use Adminaut\Service\AccessControlService;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class SettingsController
 * @package Adminaut\Controller
 */
class SettingsController extends AdminautBaseController
{

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        if (!$this->isAllowed('settings', AccessControlService::READ)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        // todo: implement settings

        return new ViewModel([
            'list' => [],
        ]);
    }
}
