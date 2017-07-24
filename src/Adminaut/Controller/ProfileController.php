<?php

namespace Adminaut\Controller;

use Adminaut\Controller\Plugin\UserAuthentication;
use Adminaut\Form\UserChangePasswordForm;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class ProfileController
 * @package Adminaut\Controller
 * @method UserAuthentication userAuthentication()
 */
class ProfileController extends AdminautBaseController
{

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        $user = $this->userAuthentication()->getIdentity();

        return new ViewModel([
            'user' => $user,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function changePasswordAction()
    {
        $form = new UserChangePasswordForm();
        $user = $this->userAuthentication()->getIdentity();

        return new ViewModel([
            'form' => $form,
            'user' => $user,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function loginsAction()
    {
        $user = $this->userAuthentication()->getIdentity();

        return new ViewModel([
            'user' => $user,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function accessTokensAction()
    {
        $user = $this->userAuthentication()->getIdentity();

        return new ViewModel([
            'user' => $user,
        ]);
    }
}
