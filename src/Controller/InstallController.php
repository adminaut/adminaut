<?php

namespace Adminaut\Controller;

use Adminaut\Form\User as UserForm;
use Adminaut\Form\InputFilter\User as UserInputFilter;
use Adminaut\Service\UserService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class InstallController
 * @package Adminaut\Controller
 */
class InstallController extends AbstractActionController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * InstallController constructor.
     * @param UserService $userService
     * @param $translator
     */
    public function __construct(UserService $userService, $translator)
    {
        $this->setUserService($userService);
        $this->setTranslator($translator);
    }

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        $user = $this->getUserService()->getUserMapper()->findFirst();
        if ($user) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $form = new UserForm(UserForm::STATUS_INSTALL);
        $form->setInputFilter(new UserInputFilter());

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                try {
                    $this->getUserService()->createSuperuser($post);
                    $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate('User has been successfully created.'));
                    return $this->redirect()->toRoute(AuthController::ROUTE_LOGIN);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
                    return $this->redirect()->toRoute('adminaut/install');
                }
            }
        }
        $this->layout()->setVariables([
            'bodyClasses' => ['login-page'],
        ]);
        $this->layout('layout/admin-blank');
        return new ViewModel([
            'form' => $form,
        ]);
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
    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }
}