<?php

namespace Adminaut\Controller;

use Adminaut\Form\InputFilter\Role as RoleInputFilter;
use Adminaut\Form\Role as RoleForm;
use Adminaut\Mapper\Role;
use Adminaut\Service\AccessControl as ACL;
use Zend\View\Model\ViewModel;

/**
 * Class AclController
 * @package Adminaut\Controller
 * @method \Adminaut\Controller\Plugin\Acl acl()
 */
class AclController extends AdminModuleBaseController
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Role
     */
    protected $roleMapper;

    public function __construct($acl, $em, $config, $roleMapper) {
        parent::__construct($acl, $em);

        $this->setConfig($config);
        $this->setRoleMapper($roleMapper);
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        if (!$this->acl()->isAllowed('Roles', ACL::READ)) {
            return $this->redirect()->toRoute('adminaut-dashboard');
        }

        $roleRepository = $this->getEntityManager()->getRepository('Adminaut\Entity\Role');
        $list = $roleRepository->findAll();

        return new ViewModel([
            'list' => $list,
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function showRoleAction()
    {
        if (!$this->acl()->isAllowed('Roles', ACL::READ)) {
            return $this->redirect()->toRoute('adminaut-dashboard');
        }

        $id = (int) $this->params()->fromRoute('roleId', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut-role');
        }

        $RoleMapper = $this->getRoleMapper();
        /**
         * @var $role \Adminaut\Entity\Role
         */
        $role = $RoleMapper->findById($id);
        if (!$role) {
            return $this->redirect()->toRoute('adminaut-role');
        }

        $AccessControl = $this->getAcl();
        $rolePermissions = $AccessControl->getPermissionsToArray($role);

        $modules = $this->getConfig()['mfcc_admin']['modules'];
        array_push($modules, ['module_name' => 'Users']);
        array_push($modules, ['module_name' => 'Roles']);

        return new ViewModel([
            'role' => $role,
            'modules' => $modules,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addRoleAction()
    {
        if (!$this->acl()->isAllowed('Roles', ACL::WRITE)) {
            return $this->redirect()->toRoute('adminaut-dashboard');
        }

        $form = new RoleForm();
        $form->setInputFilter(new RoleInputFilter());
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $AccessControl = $this->getAcl();
                    $role = $AccessControl->createRole($post);
                    $this->flashMessenger()->addSuccessMessage('Role has been successfully added.');
                    return $this->redirect()->toRoute('adminaut-acl/update-role', ['roleId' => $role->getId()]);
                } catch(\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Error: '.$e->getMessage());
                    return $this->redirect()->toRoute('adminaut-acl/add-role');
                }
            }
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function updateRoleAction()
    {
        if (!$this->acl()->isAllowed('Roles', ACL::WRITE)) {
            return $this->redirect()->toRoute('adminaut-dashboard');
        }

        $id = (int) $this->params()->fromRoute('roleId', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut-acl');
        }

        $roleMapper = $this->getRoleMapper();
        /**
         * @var $role \Adminaut\Entity\Role
         */
        $role = $roleMapper->findById($id);
        if (!$role) {
            return $this->redirect()->toRoute('adminaut-acl');
        }

        /* @var $AccessControl \Adminaut\Service\AccessControl */
        $AccessControl = $this->getAcl();

        $form = $AccessControl->getRoleForm($role);
        $form->populateValues($role->toArray());
        $form->setInputFilter(new RoleInputFilter());
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $AccessControl = $this->getAcl();
                    $AccessControl->updateRole($role, $post);
                    $AccessControl->updateRolePermissions($role, $post);
                    $this->flashMessenger()->addSuccessMessage('Role has been successfully updated.');
                } catch(\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Error: '.$e->getMessage());
                }
                return $this->redirect()->toRoute('adminaut-acl/update-role', ['roleId' => $id]);
            }
        }

        return new ViewModel([
            'form' => $form,
            'role' => $role,
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteRoleAction()
    {
        if (!$this->acl()->isAllowed('Roles', ACL::WRITE)) {
            return $this->redirect()->toRoute('adminaut-dashboard');
        }

        $id = (int) $this->params()->fromRoute('roleId', 0);
        if ($id) {
            $roleMapper = $this->getRoleMapper();
            /**
             * @var $role \Adminaut\Entity\Role
             */
            $role = $roleMapper->findById($id);
            if ($role) {
                try {
                    $AccessControl = $this->getAcl();
                    $AccessControl->deleteRole($role);
                    $this->flashMessenger()->addSuccessMessage('Role has been successfully deleted.');
                } catch(\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Error: ' . $e->getMessage());
                }
            }
        }
        return $this->redirect()->toRoute('adminaut-acl');
    }





    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return Role
     */
    public function getRoleMapper()
    {
        return $this->roleMapper;
    }

    /**
     * @param Role $roleMapper
     */
    public function setRoleMapper($roleMapper)
    {
        $this->roleMapper = $roleMapper;
    }
}