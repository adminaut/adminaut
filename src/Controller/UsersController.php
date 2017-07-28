<?php

namespace Adminaut\Controller;

use Adminaut\Controller\Plugin\AclPlugin;
use Adminaut\Entity\UserEntity;
use Adminaut\Form\InputFilter\UserInputFilter;
use Adminaut\Manager\ModuleManager;
use Adminaut\Mapper\ModuleMapper;
use Adminaut\Mapper\UserMapper;
use Adminaut\Options\ModuleOptions;
use Adminaut\Repository\UserRepository;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\UserService;
use Zend\View\Model\ViewModel;

/**
 * Class UsersController
 * @package Adminaut\Controller
 * @method AclPlugin acl();
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

    /**
     * @var ModuleManager
     */
    protected $adminModuleManager;

    /**
     * @var ModuleManager
     */
    protected $moduleManagerService;


    public function __construct($config, $acl, $em, $translator, $userMapper, $userService, $moduleManager)
    {
        parent::__construct($config, $acl, $em, $translator);
        $this->setUserMapper($userMapper);
        $this->setUserService($userService);
        $this->setModuleManagerService($moduleManager);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        if (false === $this->acl()->isAllowed('users', AccessControlService::READ)) {
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

        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $userService = $this->getUserMapper();
        $user = $userService->findById($id);
        if (!$user) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        return new ViewModel([
            'user' => $user,
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

//        $form = new UserForm(UserForm::STATUS_ADD);

        $form = $this->getAdminModuleManager()->getForm();
//        $form->setInputFilter(new UserInputFilter());

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
                    $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate('User has been successfully created.'));
                    switch ($post['submit']) {
                        case 'create-and-continue' :
                            return $this->redirect()->toRoute('adminaut/users/update', ['id' => $user->getId()]);
                        case 'create' :
                        default :
                            return $this->redirect()->toRoute('adminaut/users');
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
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

        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $userService = $this->getUserMapper();
        $user = $userService->findById($id);
        if (!$user) {
            return $this->redirect()->toRoute('adminaut/users');
        }

//        $form = new UserForm(UserForm::STATUS_UPDATE);

        /* @var $form \Adminaut\Form\Form */
        $form = $this->getAdminModuleManager()->getForm();
        $form->setInputFilter(new UserInputFilter());

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;

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
                    $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate('User has been successfully updated.'));

                    switch ($post['submit']) {
                        case 'save-and-continue' :
                            return $this->redirect()->toRoute('adminaut/users/edit', ['id' => $id]);
                        case 'save' :
                        default :
                            return $this->redirect()->toRoute('adminaut/users');
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
                }
            }
        }

        return new ViewModel([
            'form' => $form,
            'tabs' => $tabs,
            'user' => $user,
            'url_params' => [
                'id' => $user->getId(),
            ],
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

        $id = (int)$this->params()->fromRoute('id', 0);
        if ($id) {
            $userRepository = $this->getEntityManager()->getRepository(UserEntity::class);
            $user = $userRepository->find($id);
            if ($user) {
                try {
                    $userService = $this->getUserService();
                    $userService->delete($user, $this->userAuthentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate('User has been successfully deleted.'));
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
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

    /**
     * @return ModuleManager
     */
    protected function getAdminModuleManager()
    {
        if (!$this->adminModuleManager instanceof ModuleManager) {
            $moduleManager = $this->getModuleManagerService();

            $config = $this->getConfig();

            $adminModuleOption = new ModuleOptions([
                'type' => 'module',
                'module_name' => 'Users',
                'module_icon' => 'fa-users',
                'entity_class' => isset($config['adminaut']['users']['user_entity_class']) ? $config['adminaut']['users']['user_entity_class'] : UserEntity::class,
            ]);
            $adminModuleOption->setModuleId('users');
            $moduleManager->setOptions($adminModuleOption);
            $moduleMapper = new moduleMapper($this->getEntityManager(), $adminModuleOption);
            $moduleManager->setMapper($moduleMapper);

            $this->adminModuleManager = $moduleManager;
            return $this->adminModuleManager;
        } else {
            return $this->adminModuleManager;
        }
    }

    /**
     * @return ModuleManager
     */
    public function getModuleManagerService()
    {
        return $this->moduleManagerService;
    }

    /**
     * @param ModuleManager $moduleManagerService
     */
    public function setModuleManagerService(ModuleManager $moduleManagerService)
    {
        $this->moduleManagerService = $moduleManagerService;
    }
}
