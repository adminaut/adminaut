<?php

namespace Adminaut\Controller;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\AuthenticationPlugin;
use Adminaut\Form\InputFilter\UserLoginChangePasswordInputFilter;
use Adminaut\Form\UserChangePasswordForm;
use Adminaut\Form\UserLoginChangePasswordForm;
use Adminaut\Form\UserLoginForm;
use Adminaut\Form\InputFilter\UserLoginInputFilter;
use Adminaut\Manager\UserManager;
use Maknz\Slack\Message;
use MassimoFilippi\SlackModule\Service\SlackService;
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
    const ROUTE_PASSWORD_CHANGE = 'adminaut/auth/change-password';

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var SlackService|null
     */
    private $slackService;

    //-------------------------------------------------------------------------

    /**
     * AuthController constructor.
     * @param AuthenticationService $authenticationService
     * @param UserManager $userManager
     * @param SlackService|null $slackService
     */
    public function __construct(AuthenticationService $authenticationService, UserManager $userManager, $slackService)
    {
        $this->authenticationService = $authenticationService;
        $this->userManager = $userManager;
        $this->slackService = $slackService;
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
                    if($this->slackService) {
                        $attachment = $this->slackService->createAttachment([
                            'fallback' => sprintf('Admin user %s logged in', $result->getIdentity()->getName()),
                            'text' => 'Admin user logged in',
                            'color' => 'good',
                            'fields' => [
                                [
                                    'title' => 'User:',
                                    'value' => $result->getIdentity()->getName(),
                                    'short' => true
                                ],
                                [
                                    'title' => 'IP:',
                                    'value' => !isset($_SERVER['HTTP_X_REAL_IP']) || empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_REAL_IP'],
                                    'short' => true
                                ],
                            ],
                        ]);

                        /** @var Message $message */
                        $message = $this->slackService->createMessage()->attach($attachment);
                        $this->slackService->sendMessage($message);
                    }

                    if($result->getIdentity()->isPasswordChangeOnNextLogon()) {
                        return $this->redirect()->toRoute(AuthController::ROUTE_PASSWORD_CHANGE);
                    }

                    if (null !== $redirect) {
                        return $this->redirect()->toUrl(rawurldecode($redirect));
                    }
                    return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
                }

                if($this->slackService) {
                    $attachment = $this->slackService->createAttachment([
                        'fallback' => sprintf('Admin user login failed with email: %s', $formData['email']),
                        'text' => 'Admin user login failed',
                        'color' => 'danger',
                        'fields' => [
                            [
                                'title' => 'Email:',
                                'value' => $formData['email'],
                                'short' => true
                            ],
                            [
                                'title' => 'IP:',
                                'value' => !isset($_SERVER['HTTP_X_REAL_IP']) || empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_REAL_IP'],
                                'short' => true
                            ],
                        ],
                    ]);

                    /** @var Message $message */
                    $message = $this->slackService->createMessage()->attach($attachment);
                    $this->slackService->sendMessage($message);
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

    public function passwordChangeAction()
    {
        if(!$this->authenticationService->hasIdentity()) {
            $this->redirect()->toRoute(self::ROUTE_LOGIN);
        }

        $form = new UserLoginChangePasswordForm();
        $form->setInputFilter(new UserLoginChangePasswordInputFilter());

        /** @var Request $request */
        $request = $this->getRequest();
        $redirect = $this->params()->fromQuery('redirect', null);

        if (true === $request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $formData = $form->getData();

                $result = $this->authenticationService->changePassword($formData['password']);

                if (true === $result->isValid()) {
                    $this->flashMessenger()->addSuccessMessage('Password has been changed.');

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
            'redirect' => $redirect
        ]);
    }

    /**
     * @return Response
     */
    public function logoutAction()
    {
        if ($this->authenticationService->hasIdentity()) {
            if($this->slackService) {
                $attachment = $this->slackService->createAttachment([
                    'fallback' => sprintf('Admin user %s logged out', $this->authenticationService->getIdentity()->getName()),
                    'text' => 'Admin user logged out',
                    'color' => 'good',
                    'fields' => [
                        [
                            'title' => 'User:',
                            'value' => $this->authenticationService->getIdentity()->getName(),
                            'short' => true
                        ],
                    ],
                ]);

                /** @var Message $message */
                $message = $this->slackService->createMessage()->attach($attachment);
                $this->slackService->sendMessage($message);
            }

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
