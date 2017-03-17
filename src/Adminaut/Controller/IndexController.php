<?php

namespace Adminaut\Controller;

/**
 * Class IndexController
 * @package Adminaut\Controller
 */
class IndexController extends AdminModuleBaseController
{
    /**
     * @return \Zend\Http\Response
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('adminaut-dashboard');
    }
}