<?php

namespace Adminaut\Controller;

use Zend\View\Model\ViewModel;

/**
 * Class DashboardController
 * @package Adminaut\Controller
 */
class DashboardController extends AdminautBaseController
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([

        ]);
    }
}