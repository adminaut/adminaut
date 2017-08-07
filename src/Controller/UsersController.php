<?php

namespace Adminaut\Controller;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Form\InputFilter\UserInputFilter;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\UserManager;
use Adminaut\Options\ModuleOptions;
use Adminaut\Options\UsersOptions;
use Adminaut\Service\AccessControlService;
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
     * @var UserManager
     */
    private $userManager;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var UsersOptions
     */
    private $usersOptions;

    //-------------------------------------------------------------------------

    /**
     * UsersController constructor.
     * @param EntityManager $entityManager
     * @param UserManager $userManager
     * @param ModuleManager $moduleManager
     * @param UsersOptions $usersOptions
     */
    public function __construct(EntityManager $entityManager, UserManager $userManager, ModuleManager $moduleManager, UsersOptions $usersOptions)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->moduleManager = $moduleManager;
        $this->usersOptions = $usersOptions;
    }

    //-------------------------------------------------------------------------

    /**
     * @param null $default
     * @return mixed
     */
    private function getId($default = null)
    {
        return $this->params()->fromRoute('id', $default);
    }

    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * @return ModuleManager
     */
    private function getModuleManager()
    {
        return $this->moduleManager;
    }

    /**
     * @return ModuleOptions
     */
    private function getModuleOptions()
    {
        $moduleOptions = new ModuleOptions([
            'type' => 'module',
            'module_id' => 'users',
            'module_name' => 'Users',
            'module_icon' => 'fa-users',
            'entity_class' => $this->usersOptions->getUserEntityClass(),
        ]);

        return $moduleOptions;
    }

    //-------------------------------------------------------------------------

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        if (false === $this->isAllowed('users', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $users = $this->getUserManager()->findAll();
        return new ViewModel([
            'list' => $users,
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

        $id = $this->getId();

        if (null === $id) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $user = $this->getUserManager()->findOneById($id);

        if (null === $user) {
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
//        $form->setInputFilter(new UserInputFilter());

        $moduleOptions = $this->getModuleOptions();
        $form = $this->getModuleManager()->createForm($moduleOptions);

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
                    if ($form->has('password')) {
                        $passwordElement = $form->get('password');
                        $password = $passwordElement->getValue();

                        if (empty(trim($password))) {
                            $form->remove('password');
                        } else {
                            $passwordElement->setValue(PasswordHelper::hash($password));
                        }
                    }

                    $user = $this->getModuleManager()->create($moduleOptions->getEntityClass(), $form, null, $this->authentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage($this->translate('User has been successfully created.'));
                    switch ($post['submit']) {
                        case 'create-and-continue' :
                            return $this->redirect()->toRoute('adminaut/users/edit', ['id' => $user->getId()]);
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

        $id = $this->getId();

        if (null === $id) {
            return $this->redirect()->toRoute('adminaut/users');
        }

        $user = $this->getUserManager()->findOneById($id);

        if (null === $user) {
            return $this->redirect()->toRoute('adminaut/users');
        }

//        $form = new UserForm(UserForm::STATUS_UPDATE);

        $moduleOptions = $this->getModuleOptions();

        $form = $this->getModuleManager()->createForm($moduleOptions);
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

                    if ($form->has('password')) {
                        $passwordElement = $form->get('password');
                        $password = $passwordElement->getValue();

                        if (empty(trim($password))) {
                            $form->remove('password');
                        } else {
                            $passwordElement->setValue(PasswordHelper::hash($password));
                        }
                    }

                    $this->getModuleManager()->update($user, $form, null, $this->authentication()->getIdentity());

                    $this->flashMessenger()->addSuccessMessage($this->translate('User has been successfully updated.'));

                    switch ($post['submit']) {
                        case 'save-and-continue' :
                            return $this->redirect()->toRoute('adminaut/users/edit', ['id' => $user->getId()]);
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

        $id = $this->getId();

        if ($id) {
            $user = $this->getUserManager()->findOneById($id);
            if ($user) {
                try {
                    $this->getUserManager()->delete($user, $this->authentication()->getIdentity());
                    $this->flashMessenger()->addSuccessMessage($this->translate('User has been successfully deleted.'));
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->translate('Error: %s'), $e->getMessage()));
                }
            }
        }
        return $this->redirect()->toRoute('adminaut/users');
    }
}
