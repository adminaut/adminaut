<?php

namespace Adminaut\Controller;

use Adminaut\Entity\UserEntity;
use Adminaut\Form\InputFilter\UserInputFilter;
use Adminaut\Manager\AdminModulesManager;
use Adminaut\Manager\ModuleManager;
use Adminaut\Mapper\ModuleMapper;
use Adminaut\Mapper\UserMapper;
use Adminaut\Options\ModuleOptions;
use Adminaut\Repository\UserRepository;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\UserService;
use Doctrine\ORM\EntityManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class UsersController
 * @package Adminaut\Controller
 */
class UsersController extends AdminautBaseController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserMapper
     */
    private $userMapper;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var AdminModulesManager
     */
    private $adminModuleManager;

    /**
     * UsersController constructor.
     * @param EntityManager $entityManager
     * @param UserMapper $userMapper
     * @param UserService $userService
     * @param ModuleManager $moduleManager
     */
    public function __construct(EntityManager $entityManager, UserMapper $userMapper, UserService $userService, ModuleManager $moduleManager)
    {
        $this->entityManager = $entityManager;
        $this->userMapper = $userMapper;
        $this->userService = $userService;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        if (false === $this->isAllowed('users', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(UserEntity::class);
        $list = $userRepository->findAll();
        return new ViewModel([
            'list' => $list,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function viewAction()
    {
        if (!$this->isAllowed('users', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $userService = $this->userMapper;
        $user = $userService->findById($id);
        if (!$user) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        return new ViewModel([
            'user' => $user,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function addAction()
    {
        if (!$this->isAllowed('users', AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

//        $form = new UserForm(UserForm::STATUS_ADD);

        $form = $this->getAdminModuleManager()->getForm();
//        $form->setInputFilter(new UserInputFilter());

        $roles = $this->config()['adminaut']['roles'];
        $rolesData = ['admin' => 'Admin'];
        foreach ($roles as $roleId => $role) {
            $rolesData[$roleId] = $role['name'];
        }
        $form->get('role')->setValueOptions($rolesData);

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $userService = $this->userService;
                    $user = $userService->add($post, $this->authentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage($this->translate('User has been successfully created.'));
                    switch ($post['submit']) {
                        case 'create-and-continue' :
                            return $this->redirect()->toRoute('adminaut/users/update', ['id' => $user->getId()]);
                        case 'create' :
                        default :
                            return $this->redirect()->toRoute('adminaut/users');
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->translate('Error: %s'), $e->getMessage()));
                    return $this->redirect()->toRoute('adminaut/users/add');
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
    public function editAction()
    {
        if (!$this->isAllowed('users', AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $userService = $this->userMapper;
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

        $roles = $this->config()['adminaut']['roles'];
        $rolesData = ['admin' => 'Admin'];
        foreach ($roles as $roleId => $role) {
            $rolesData[$roleId] = $role['name'];
        }
        $form->get('role')->setValueOptions($rolesData);

        $form->populateValues($user->toArray());

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $userService = $this->userService;
                    $userService->update($user, $post, $this->authentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage($this->translate('User has been successfully updated.'));

                    switch ($post['submit']) {
                        case 'save-and-continue' :
                            return $this->redirect()->toRoute('adminaut/users/edit', ['id' => $id]);
                        case 'save' :
                        default :
                            return $this->redirect()->toRoute('adminaut/users');
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->translate('Error: %s'), $e->getMessage()));
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
     * @return Response
     */
    public function deleteAction()
    {
        if (!$this->isAllowed('users', AccessControlService::FULL)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $id = (int)$this->params()->fromRoute('id', 0);
        if ($id) {
            $userRepository = $this->entityManager->getRepository(UserEntity::class);
            $user = $userRepository->find($id);
            if ($user) {
                try {
                    $userService = $this->userService;
                    $userService->delete($user, $this->authentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage($this->translate('User has been successfully deleted.'));
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->translate('Error: %s'), $e->getMessage()));
                }
            }
        }
        return $this->redirect()->toRoute('adminaut/users');
    }

    /**
     * @return ModuleManager
     */
    protected function getAdminModuleManager()
    {
        if (!$this->adminModuleManager instanceof ModuleManager) {
            $moduleManager = $this->moduleManager;

            $config = $this->config();

            $adminModuleOption = new ModuleOptions([
                'type' => 'module',
                'module_name' => 'Users',
                'module_icon' => 'fa-users',
                'entity_class' => isset($config['adminaut']['users']['user_entity_class']) ? $config['adminaut']['users']['user_entity_class'] : UserEntity::class,
            ]);
            $adminModuleOption->setModuleId('users');
            $moduleManager->setOptions($adminModuleOption);
            $moduleMapper = new moduleMapper($this->entityManager, $adminModuleOption);
            $moduleManager->setMapper($moduleMapper);

            $this->adminModuleManager = $moduleManager;
            return $this->adminModuleManager;
        } else {
            return $this->adminModuleManager;
        }
    }
}
