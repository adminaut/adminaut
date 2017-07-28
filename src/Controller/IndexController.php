<?php

namespace Adminaut\Controller;

use Zend\Http\Response;

/**
 * Class IndexController
 * @package Adminaut\Controller
 */
class IndexController extends AdminautBaseController
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('adminaut/dashboard');
    }
}
