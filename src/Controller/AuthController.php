<?php

namespace Adminaut\Controller;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\AuthenticationPlugin;
use Adminaut\Form\UserLoginForm;
use Adminaut\Form\InputFilter\UserLoginInputFilter;
use Adminaut\Manager\UserManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class AuthController
 * @package Adminaut\Controller
 * @method AuthenticationPlugin authentication()
 * @method FlashMessenger flashMessenger()
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
     * @var UserManager
     */
    private $userManager;

    //-------------------------------------------------------------------------

    /**
     * AuthController constructor.
     * @param AuthenticationService $authenticationService
     * @param UserManager $userManager
     */
    public function __construct(AuthenticationService $authenticationService, UserManager $userManager)
    {
        $this->authenticationService = $authenticationService;
        $this->userManager = $userManager;
    }

    //-------------------------------------------------------------------------

    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->userManager;
    }

    //-------------------------------------------------------------------------

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
        if (0 === $this->getUserManager()->countAll()) {
            return $this->redirect()->toRoute(InstallController::ROUTE_INDEX);
        }

        if (true === $this->authenticationService->hasIdentity()) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
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
                    return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
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
