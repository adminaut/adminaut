<?php

// todo: Refactor this controller so users can decide, if they want use database for roles, or config array.

namespace Adminaut\Controller;

use Adminaut\Entity\RoleEntity;
use Adminaut\Form\InputFilter\RoleInputFilter;
use Adminaut\Form\RoleForm;
use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class AclController
 * @package Adminaut\Controller
 */
class AclController extends AdminautBaseController
{
    const ROUTE_INDEX = 'adminaut/acl';
    const ROUTE_ADD_ROLE = 'adminaut/acl/add-role';
    const ROUTE_EDIT_ROLE = 'adminaut/acl/update-role'; // todo: change update to edit

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var RoleMapper
     */
    private $roleMapper;

    /**
     * AclController constructor.
     * @param EntityManager $entityManager
     * @param RoleMapper $roleMapper
     */
    public function __construct(EntityManager $entityManager, RoleMapper $roleMapper)
    {
        $this->entityManager = $entityManager;
        $this->roleMapper = $roleMapper;
    }

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        if (!$this->acl()->isAllowed('Roles', AccessControlService::READ)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $roleRepository = $this->entityManager->getRepository(RoleEntity::class);
        $list = $roleRepository->findAll();

        return new ViewModel([
            'list' => $list,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function showRoleAction()
    {
        if (!$this->acl()->isAllowed('Roles', AccessControlService::READ)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $id = (int)$this->params()->fromRoute('roleId', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut-role');
        }

        /**
         * @var $role \Adminaut\Entity\RoleEntity
         */
        $role = $this->roleMapper->findById($id);
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
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function addRoleAction()
    {
        if (!$this->acl()->isAllowed('Roles', AccessControlService::WRITE)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
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
                    $this->addSuccessMessage('Role has been successfully added.');
                    return $this->redirect()->toRoute(self::ROUTE_EDIT_ROLE, ['roleId' => $role->getId()]);
                } catch (\Exception $e) {
                    $this->addErrorMessage('Error: ' . $e->getMessage());
                    return $this->redirect()->toRoute(self::ROUTE_ADD_ROLE);
                }
            }
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function updateRoleAction()
    {
        if (!$this->acl()->isAllowed('Roles', AccessControlService::WRITE)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $id = (int)$this->params()->fromRoute('roleId', 0);
        if (!$id) {
            return $this->redirect()->toRoute(self::ROUTE_INDEX);
        }

        $roleMapper = $this->getRoleMapper();
        /**
         * @var $role \Adminaut\Entity\RoleEntity
         */
        $role = $roleMapper->findById($id);
        if (!$role) {
            return $this->redirect()->toRoute(self::ROUTE_INDEX);
        }

        $AccessControl = $this->acl()->getAcl();

        $form = $AccessControl->getRoleForm($role);
        $form->populateValues($role->toArray());
        $form->setInputFilter(new RoleInputFilter());
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $AccessControl->updateRole($role, $post);
                    $AccessControl->updateRolePermissions($role, $post);
                    $this->addSuccessMessage('Role has been successfully updated.');
                } catch (\Exception $e) {
                    $this->addErrorMessage('Error: ' . $e->getMessage());
                }
                return $this->redirect()->toRoute(self::ROUTE_EDIT_ROLE, ['roleId' => $id]);
            }
        }

        return new ViewModel([
            'form' => $form,
            'role' => $role,
        ]);
    }

    /**
     * @return Response
     */
    public function deleteRoleAction()
    {
        if (!$this->acl()->isAllowed('Roles', AccessControlService::WRITE)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $id = (int)$this->params()->fromRoute('roleId', 0);
        if ($id) {
            $roleMapper = $this->getRoleMapper();
            /**
             * @var $role \Adminaut\Entity\RoleEntity
             */
            $role = $roleMapper->findById($id);
            if ($role) {
                try {
                    $AccessControl = $this->getAcl();
                    $AccessControl->deleteRole($role);
                    $this->addSuccessMessage('Role has been successfully deleted.');
                } catch (\Exception $e) {
                    $this->addErrorMessage('Error: ' . $e->getMessage());
                }
            }
        }
        return $this->redirect()->toRoute(self::ROUTE_INDEX);
    }
}