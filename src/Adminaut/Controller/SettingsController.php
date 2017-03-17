<?php

namespace Adminaut\Controller;

use Adminaut\Service\AccessControl as ACL;

use Adminaut\Service\AccessControl;
use Zend\View\Model\ViewModel;

/**
 * Class SettingsController
 * @package Adminaut\Controller
 */
class SettingsController extends AdminModuleBaseController
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        if (!$this->acl()->isAllowed('settings', AccessControl::READ)) {
            return $this->redirect()->toRoute('adminaut-dashboard');
        }

        $roleRepository = $this->getEntityManager()->getRepository('Adminaut\Entity\Role');
        $list = $roleRepository->findAll();

        return new ViewModel([
            'list' => $list,
        ]);
    }
}