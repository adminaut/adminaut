<?php

namespace Adminaut\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ProfileController
 * @package Adminaut\Controller
 */
class ProfileController extends AbstractActionController
{

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function changePasswordAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function loginsAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function accessTokensAction()
    {
        return new ViewModel();
    }
}
