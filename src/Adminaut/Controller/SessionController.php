<?php

namespace Adminaut\Controller;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\UserAuthentication;
use Adminaut\Form\UserLogin as UserLoginForm;
use Adminaut\Form\InputFilter\UserLogin as UserLoginInputFilter;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class SessionController
 * @package Adminaut\Controller
 * @method UserAuthentication userAuthentication()
 */
class SessionController extends AbstractActionController
{
    /**
     * Constants
     */
    const ROUTE_LOGIN = 'adminaut/session/login';
    const ROUTE_LOGOUT = 'adminaut/session/logout';
    const ROUTE_FORGOTTEN_PASSWORD = 'adminaut/session/forgotten-password';
    const ROUTE_REQUEST_ACCESS = 'adminaut/session/request-access';

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * SessionController constructor.
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
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
