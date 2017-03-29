<?php

namespace Adminaut\Controller;

use Adminaut\Controller\Plugin\Acl;
use Adminaut\Entity\UserEntity;
use Adminaut\Form\User as UserForm;
use Adminaut\Form\InputFilter\User as UserInputFilter;
use Adminaut\Mapper\UserMapper;
use Adminaut\Repository\UserRepository;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\UserService;
use Zend\View\Model\ViewModel;

/**
 * Class UsersController
 * @package Adminaut\Controller
 * @method Acl acl();
 */
class UsersController extends AdminautBaseController
{
    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var UserService
     */
    protected $userService;


    public function __construct($config, $acl, $em, $userMapper, $userService)
    {
        parent::__construct($config, $acl, $em);
        $this->setUserMapper($userMapper);
        $this->setUserService($userService);
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        if (!$this->acl()->isAllowed('users', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        /** @var UserRepository $userRepository */
        $userRepository = $this->getEntityManager()->getRepository(UserEntity::class);
        $list = $userRepository->getList();
        return new ViewModel([
            'list' => $list,
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function viewAction()
    {
        if (!$this->acl()->isAllowed('users', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $userService = $this->getUserMapper();
        $user = $userService->findById($id);
        if (!$user) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        return new ViewModel([
            'user' => $user
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        if (!$this->acl()->isAllowed('users', AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $form = new UserForm(UserForm::STATUS_ADD);
        $form->setInputFilter(new UserInputFilter());

        $roles = $this->config['adminaut']['roles'];
        $rolesData = ['admin' => 'Admin'];
        foreach ($roles as $roleId => $role) {
            $rolesData[$roleId] = $role['name'];
        }
        $form->get('role')->setValueOptions($rolesData);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $userService = $this->getUserService();
                    $user = $userService->add($post, $this->userAuthentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage('User has been successfully created');
                    switch($post['submit']) {
                        case 'create-and-continue' :
                            return $this->redirect()->toRoute('adminaut/users/update', ['id' => $user->getId()]);
                        case 'create' :
                        default :
                            return $this->redirect()->toRoute('adminaut/users');
                    }
                } catch(\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Error: '.$e->getMessage());
                    return $this->redirect()->toRoute('adminaut/users/add');
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
    public function editAction()
    {
        if (!$this->acl()->isAllowed('users', AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $userService = $this->getUserMapper();
        $user = $userService->findById($id);
        if (!$user) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $form = new UserForm(UserForm::STATUS_UPDATE);
        $form->setInputFilter(new UserInputFilter());

        $roles = $this->config['adminaut']['roles'];
        $rolesData = ['admin' => 'Admin'];
        foreach ($roles as $roleId => $role) {
            $rolesData[$roleId] = $role['name'];
        }
        $form->get('role')->setValueOptions($rolesData);

        $form->populateValues($user->toArray());

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $userService = $this->getUserService();
                    $userService->update($user, $post, $this->userAuthentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage('User has been successfully updated.');

                    switch($post['submit']) {
                        case 'save-and-continue' :
                            return $this->redirect()->toRoute('adminaut/users/edit', ['id' => $id]);
                        case 'save' :
                        default :
                        return $this->redirect()->toRoute('adminaut/users');
                    }
                } catch(\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Error: '.$e->getMessage());
                }
            }
        }

        return new ViewModel([
            'form' => $form,
            'user' => $user,
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        if (!$this->acl()->isAllowed('users', AccessControlService::FULL)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id) {
            $userRepository = $this->getEntityManager()->getRepository(UserEntity::class);
            $user = $userRepository->find($id);
            if ($user) {
                try {
                    $userService = $this->getUserService();
                    $userService->delete($user, $this->userAuthentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage('User has been successfully deleted.');
                } catch(\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Error: ' . $e->getMessage());
                }
            }
        }
        return $this->redirect()->toRoute('adminaut/users');
    }




    /**
     * @return UserMapper
     */
    public function getUserMapper()
    {
        return $this->userMapper;
    }

    /**
     * @param UserMapper $userMapper
     */
    public function setUserMapper($userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        return $this->userService;
    }

    /**
     * @param UserService $userService
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }
}