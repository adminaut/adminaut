<?php

namespace Adminaut\Controller;

use Adminaut\Form\UserLogin as UserLoginForm;
use Adminaut\Form\InputFilter\UserLogin as UserLoginInputFilter;
use Adminaut\Options\UserOptions;
use Adminaut\Service\UserService;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

/**
 * Class UserController
 * @package Adminaut\Controller
 */
class UserController extends AbstractActionController
{
    const ROUTE_CHANGEPASSWD = 'adminaut/user/changepassword';
    const ROUTE_LOGIN = 'adminaut/user/login';
    const ROUTE_REGISTER = 'adminaut/user/register';
    const ROUTE_CHANGEEMAIL  = 'adminaut/user/changeemail';
    const CONTROLLER_NAME = self::class;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Form
     */
    protected $loginForm;

    /**
     * @var Form
     */
    protected $registerForm;

    /**
     * @var Form
     */
    protected $changePasswordForm;

    /**
     * @var Form
     */
    protected $changeEmailForm;

    /**
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'Authentication failed. Please try again.';

    /**
     * @var UserOptions
     */
    protected $userOptions;

    /**
     * @var callable $redirectCallback
     */
    protected $redirectCallback;

    /**
     * @param callable $redirectCallback
     */
    public function __construct($redirectCallback, $userService, $userOptions)
    {
        if (!is_callable($redirectCallback)) {
            throw new \InvalidArgumentException('You must supply a callable redirectCallback');
        }
        $this->redirectCallback = $redirectCallback;
        $this->setUserService($userService);
        $this->setUserOptions($userOptions);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        if (!$this->userAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $this->layout('layout/admin');
        return new ViewModel();
    }

    /**
     * @return array|mixed|\Zend\Http\Response
     */
    public function loginAction()
    {
        if ($this->userAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getUserOptions()->getLoginRedirectRoute());
        }

        // check superuser, if not exist, create
        if (!$this->getUserService()->checkSuperuser()){
            return $this->redirect()->toRoute('adminaut/install');
        }

        $request = $this->getRequest();
        $form = $this->getLoginForm();
        if ($this->getUserOptions()->isUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        $this->layout()->setVariables([
            'bodyClasses' => ['login-page']
        ]);
        $this->layout('layout/admin-blank');

        if (!$request->isPost()) {
            return [
                'form' => $form,
                'redirect'  => $redirect,
            ];
        }
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN).($redirect ? '?redirect='. rawurlencode($redirect) : ''));
        }
        $this->userAuthentication()->getAuthAdapter()->resetAdapters();
        $this->userAuthentication()->getAuthService()->clearIdentity();
        return $this->forward()->dispatch(static::CONTROLLER_NAME, ['action' => 'authenticate']);
    }

    public function forgotPasswordAction() {
        if ($this->userAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getUserOptions()->getLoginRedirectRoute());
        }

        $this->layout()->setVariables([
            'bodyClasses' => ['login-page']
        ]);
        $this->layout('layout/admin-blank');

        return new ViewModel();
    }

    /**
     * @return mixed
     */
    public function logoutAction()
    {
        $this->userAuthentication()->getAuthAdapter()->resetAdapters();
        $this->userAuthentication()->getAuthAdapter()->logoutAdapters();
        $this->userAuthentication()->getAuthService()->clearIdentity();
        $redirect = $this->redirectCallback;
        return $redirect();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function authenticateAction()
    {
        if ($this->userAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        $adapter = $this->userAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));
        $result = $adapter->prepareForAuthentication($this->getRequest());
        if ($result instanceof Response) {
            return $result;
        }
        $auth = $this->userAuthentication()->getAuthService()->authenticate($adapter);
        if (!$auth->isValid()) {
            $this->flashMessenger()->addErrorMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            return $this->redirect()->toUrl(
                $this->url()->fromRoute(static::ROUTE_LOGIN) .
                ($redirect ? '?redirect='. rawurlencode($redirect) : '')
            );
        }
        $redirect = $this->redirectCallback;
        return $redirect();
    }

    /**
     * @return Form
     */
    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $form = new UserLoginForm();
            $form->setInputFilter(new UserLoginInputFilter());
            $this->setLoginForm($form);
        }
        return $this->loginForm;
    }

    /**
     * @param Form $loginForm
     * @return $this
     */
    public function setLoginForm(Form $loginForm)
    {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->setMessages([
                'identity' => [
                    $fm[0]
                ]
            ]);
        }
        return $this;
    }

    /**
     * @return UserService
     */
    public function getUserService(): UserService
    {
        return $this->userService;
    }

    /**
     * @param UserService $userService
     */
    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return UserOptions
     */
    public function getUserOptions(): UserOptions
    {
        return $this->userOptions;
    }

    /**
     * @param UserOptions $userOptions
     */
    public function setUserOptions(UserOptions $userOptions)
    {
        $this->userOptions = $userOptions;
    }
}
