<?php

namespace Adminaut\Controller;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\UserAuthentication;
use Adminaut\Form\UserLoginForm;
use Adminaut\Form\InputFilter\UserLoginInputFilter;
use Adminaut\Service\UserService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class AuthController
 * @package Adminaut\Controller
 * @method UserAuthentication userAuthentication()
 */
class AuthController extends AbstractActionController
{
    /**
     * Constants
     */
    const ROUTE_INDEX = 'adminaut/auth';
    const ROUTE_LOGIN = 'adminaut/auth/login';
    const ROUTE_LOGOUT = 'adminaut/auth/logout';
    const ROUTE_FORGOTTEN_PASSWORD = 'adminaut/auth/forgotten-password';
    const ROUTE_REQUEST_ACCESS = 'adminaut/auth/request-access';

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * AuthController constructor.
     * @param AuthenticationService $authenticationService
     * @param UserService $userService
     */
    public function __construct(AuthenticationService $authenticationService, UserService $userService)
    {
        $this->authenticationService = $authenticationService;
        $this->userService = $userService;
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute(self::ROUTE_LOGIN);
    }

    /**
     * @return Response|ViewModel
     */
    public function loginAction()
    {
        if (true === $this->authenticationService->hasIdentity()) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        if (null === $this->userService->getUserMapper()->findFirst()) {
            return $this->redirect()->toRoute('adminaut/install');
        }

        $form = new UserLoginForm();
        $form->setInputFilter(new UserLoginInputFilter());

        $redirect = $this->params()->fromQuery('redirect', null);

        /** @var Request $request */
        $request = $this->getRequest();

        if (true === $request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $formData = $form->getData();

                $result = $this->authenticationService->authenticate($formData['email'], $formData['password']);

                if (true === $result->isValid()) {
                    if (null !== $redirect) {
                        return $this->redirect()->toUrl(rawurldecode($redirect));
                    }
                    return $this->redirect()->toRoute('adminaut/dashboard');
                }

                foreach ($result->getMessages() as $message) {
                    $this->flashMessenger()->addWarningMessage($message);
                }
            }
        }

        $this->layout()->setVariables([
            'bodyClasses' => ['login-page'],
        ]);
        $this->layout('layout/admin-blank');

        return new ViewModel([
            'form' => $form,
            'redirect' => $redirect,
        ]);
    }

    /**
     * @return Response
     */
    public function logoutAction()
    {
        if ($this->authenticationService->hasIdentity()) {
            $this->authenticationService->clearIdentity();
        }

        return $this->redirect()->toRoute(self::ROUTE_LOGIN);
    }

    /**
     * @return Response
     */
    public function forgottenPasswordAction()
    {
        // todo: implement
        return $this->indexAction();
    }

    /**
     * @return Response
     */
    public function requestAccessAction()
    {
        // todo: implement
        return $this->indexAction();
    }
}
