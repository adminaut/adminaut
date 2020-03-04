<?php

namespace Adminaut\Controller;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\UserManager;
use Adminaut\Options\ModuleOptions;
use Adminaut\Options\UsersOptions;
use Adminaut\Service\AccessControlService;
use Adminaut\Datatype\Checkbox;
use Adminaut\Service\MailService;
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
     * Routes.
     */
    const ROUTE_INDEX = 'adminaut/users';
    const ROUTE_ADD = 'adminaut/users/add';
    const ROUTE_EDIT = 'adminaut/users/edit';
    const ROUTE_DELETE = 'adminaut/users/delete';

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
     * @var MailService
     */
    private $mailService;

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
    public function __construct(EntityManager $entityManager, UserManager $userManager, ModuleManager $moduleManager, $mailService, UsersOptions $usersOptions)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->moduleManager = $moduleManager;
        $this->mailService = $mailService;
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
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
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
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $id = $this->getId();

        if (null === $id) {
            return $this->redirect()->toRoute(self::ROUTE_INDEX);
        }

        /** @var AdminautEntityInterface $user */
        $user = $this->getUserManager()->findOneById($id);

        if (null === $user) {
            $this->addErrorMessage($this->translate('User was not found.', 'adminaut'));
            return $this->redirect()->toRoute(self::ROUTE_INDEX);
        }


        $moduleOptions = $this->getModuleOptions();

        $form = $this->getModuleManager()->createForm($moduleOptions);

        $form->bind($user);

        $elements = [];

        /* @var $element \Zend\Form\Element */
        foreach ($form->getElements() as $key => $element) {
            $elements[$element->getName()] = $element;
        }

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;

        return new ViewModel([
            'user' => $user,
            'elements' => $elements,
            'widgets' => $form->getWidgets(),
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function addAction()
    {
        if (!$this->isAllowed('users', AccessControlService::WRITE)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

//        $form = new UserForm(UserForm::STATUS_ADD);
//        $form->setInputFilter(new UserInputFilter());

        $moduleOptions = $this->getModuleOptions();
        $form = $this->getModuleManager()->createForm($moduleOptions);
        $entityClass= $moduleOptions->getEntityClass();
        $form->bind(new $entityClass);

        $roles = $this->config()['adminaut']['roles'];
        $rolesData = ['admin' => 'Admin'];
        foreach ($roles as $roleId => $role) {
            $rolesData[$roleId] = $role['name'];
        }
        $form->get('role')->setValueOptions($rolesData);
        $form->add([
            'type' => Checkbox::class,
            'name' => 'generatePassword',
            'options' => [
                'label' => $this->translate('Generate password', 'adminaut'),
            ],
        ], [
            'priority' => 14
        ]);
        $form->add([
            'type' => Checkbox::class,
            'name' => 'sendAccountInformation',
            'options' => [
                'label' => $this->translate('Send account information to the user', 'adminaut')
            ],
        ], [
            'priority' => 13
        ]);

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $generatePassword = $form->get('generatePassword')->getValue();
                    $passwordElement = $form->get('password');

                    if($generatePassword) {
                        $password = PasswordHelper::generate(16, true);
                        $passwordElement->setValue(PasswordHelper::hash($password));
                    } else {
                        $password = $passwordElement->getValue();

                        if (empty(trim($password))) {
                            $form->remove('password');
                        } else {
                            $passwordElement->setValue(PasswordHelper::hash($password));
                        }
                    }

                    $user = $this->getModuleManager()->create($moduleOptions->getEntityClass(), $form, null, $this->authentication()->getIdentity());
                    $this->addSuccessMessage($this->translate('User has been successfully created.', 'adminaut'));

                    if($this->mailService && $form->has('sendAccountInformation')) {
                        if($form->get('sendAccountInformation')->getValue() && !empty(trim($password))) {
                            try {
                                $this->mailService->sendAccountInformation($user, $password, $this->mailService::ACCOUNT_INFORMATION_OPERATION_CREATE);
                            } catch(\Exception $e) {
                                $this->addErrorMessage(sprintf($this->translate('Exception while sending account information: %S', 'adminaut'), $e->getMessage()));
                            }
                        }
                    }

                    switch ($post['submit']) {
                        case 'create-and-continue' :
                            return $this->redirect()->toRoute(self::ROUTE_EDIT, ['id' => $user->getId()]);
                        case 'create' :
                        default :
                            return $this->redirect()->toRoute(self::ROUTE_INDEX);
                    }
                } catch (\Exception $e) {
                    $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
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
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $id = $this->getId();

        if (null === $id) {
            return $this->redirect()->toRoute(self::ROUTE_INDEX);
        }

        $user = $this->getUserManager()->findOneById($id);

        if (null === $user) {
            return $this->redirect()->toRoute(self::ROUTE_INDEX);
        }

//        $form = new UserForm(UserForm::STATUS_UPDATE);

        $moduleOptions = $this->getModuleOptions();

        $form = $this->getModuleManager()->createForm($moduleOptions);
//        $form->setInputFilter(new UserInputFilter());

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;

        $roles = $this->config()['adminaut']['roles'];
        $rolesData = ['admin' => 'Admin'];
        foreach ($roles as $roleId => $role) {
            $rolesData[$roleId] = $role['name'];
        }
        $form->get('role')->setValueOptions($rolesData);
        $form->add([
            'type' => Checkbox::class,
            'name' => 'generatePassword',
            'options' => [
                'label' => _('Generate password'),
            ],
        ], [
            'priority' => 14
        ]);
        $form->add([
            'type' => Checkbox::class,
            'name' => 'sendAccountInformation',
            'options' => [
                'label' => _('Send account information to the user')
            ],
        ], [
            'priority' => 13
        ]);

        $form->bind($user);
//        $form->populateValues($user->toArray());

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    $generatePassword = $form->get('generatePassword')->getValue();
                    $passwordElement = $form->get('password');

                    if($generatePassword) {
                        $password = PasswordHelper::generate(16, true);
                        $passwordElement->setValue(PasswordHelper::hash($password));
                    } else {
                        $password = $passwordElement->getValue();

                        if (empty(trim($password))) {
                            $form->remove('password');
                        } else {
                            $passwordElement->setValue(PasswordHelper::hash($password));
                        }
                    }

                    $this->getModuleManager()->update($user, $form, null, $this->authentication()->getIdentity());
                    $this->addSuccessMessage($this->translate('User has been successfully updated.', 'adminaut'));

                    if($this->mailService && $form->has('sendAccountInformation')) {
                        if($form->get('sendAccountInformation')->getValue() && !empty(trim($password))) {
                            try {
                                $this->mailService->sendAccountInformation($user, $password, $this->mailService::ACCOUNT_INFORMATION_OPERATION_UPDATE);
                            } catch(\Exception $e) {
                                $this->addErrorMessage(sprintf($this->translate('Exception while sending account information: %s', 'adminaut'), $e->getMessage()));
                            }
                        }
                    }

                    switch ($post['submit']) {
                        case 'save-and-continue' :
                            return $this->redirect()->toRoute(self::ROUTE_EDIT, ['id' => $user->getId()]);
                        case 'save' :
                        default :
                            return $this->redirect()->toRoute(self::ROUTE_INDEX);
                    }
                } catch (\Exception $e) {
                    $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
                }
            }
        }

        return new ViewModel([
            'form' => $form,
            'tabs' => $tabs,
            'user' => $user,
            'widgets' => $form->getWidgets(),
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
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $id = $this->getId();

        if ($id) {
            $user = $this->getUserManager()->findOneById($id);
            if ($user) {
                try {
                    $this->getUserManager()->delete($user, $this->authentication()->getIdentity());
                    $this->addSuccessMessage($this->translate('User has been successfully deleted.', 'adminaut'));
                } catch (\Exception $e) {
                    $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
                }
            }
        }
        return $this->redirect()->toRoute(self::ROUTE_INDEX);
    }
}
