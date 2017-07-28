<?php

namespace Adminaut\Controller;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Entity\UserAccessTokenEntity;
use Adminaut\Entity\UserLoginEntity;
use Adminaut\Form\InputFilter\UserChangePasswordInputFilter;
use Adminaut\Form\UserChangePasswordForm;
use Adminaut\Repository\UserAccessTokenRepository;
use Adminaut\Repository\UserLoginRepository;
use Doctrine\ORM\EntityManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class ProfileController
 * @package Adminaut\Controller
 */
class ProfileController extends AdminautBaseController
{
    /**
     * Constants.
     */
    const ROUTE_INDEX = 'adminaut/profile';
    const ROUTE_CHANGE_PASSWORD = 'adminaut/profile/change-password';
    const ROUTE_LOGINS = 'adminaut/profile/logins';
    const ROUTE_ACCESS_TOKENS = 'adminaut/profile/access-tokens';
    const ROUTE_ACCESS_TOKENS_DELETE = 'adminaut/profile/access-tokens/delete';
    const ROUTE_ACCESS_TOKENS_DELETE_ALL = 'adminaut/profile/access-tokens/delete-all';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ProfileController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
        $form->setInputFilter(new UserChangePasswordInputFilter());

        $user = $this->userAuthentication()->getIdentity();

        /** @var Request $request */
        $request = $this->getRequest();

        if (true === $request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $formData = $form->getData();

                if (true === PasswordHelper::verify($formData['password'], $user->getPassword())) {
                    $newPassword = $formData['newPassword'];
                    $newPasswordHash = PasswordHelper::hash($newPassword);

                    $user->setPassword($newPasswordHash);
                    $this->entityManager->flush($user);

                    $this->flashMessenger()->addSuccessMessage(_('Password has been changed.'));

                    return $this->redirect()->toRoute(self::ROUTE_CHANGE_PASSWORD);
                } else {
                    $this->flashMessenger()->addWarningMessage(_('Password does not match current password.'));
                }
            }
        }

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

        /** @var UserLoginRepository $ulr */
        $ulr = $this->entityManager->getRepository(UserLoginEntity::class);
        $logins = $ulr->findBy(['user' => $user], ['id' => 'desc']);

        return new ViewModel([
            'user' => $user,
            'logins' => $logins,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function accessTokensAction()
    {
        $user = $this->userAuthentication()->getIdentity();

        /** @var UserAccessTokenRepository $atr */
        $atr = $this->entityManager->getRepository(UserAccessTokenEntity::class);
        $accessTokens = $atr->findBy(['user' => $user]);

        return new ViewModel([
            'user' => $user,
            'accessTokens' => $accessTokens,
        ]);
    }

    /**
     * @return Response
     */
    public function deleteAccessTokenAction()
    {
        $user = $this->userAuthentication()->getIdentity();
        $id = $this->params('id');

        if (null !== $id) {

            /** @var UserAccessTokenRepository $atr */
            $atr = $this->entityManager->getRepository(UserAccessTokenEntity::class);
            $accessToken = $atr->findOneBy(['id' => $id, 'user' => $user]);

            if (null !== $accessToken) {
                $this->entityManager->remove($accessToken);
                $this->entityManager->flush();
            }
        }

        return $this->redirect()->toRoute(self::ROUTE_ACCESS_TOKENS);
    }

    /**
     * @return Response
     */
    public function deleteAllAccessTokensAction()
    {
        $user = $this->userAuthentication()->getIdentity();

        /** @var UserAccessTokenRepository $atr */
        $atr = $this->entityManager->getRepository(UserAccessTokenEntity::class);
        $accessTokens = $atr->findBy(['user' => $user]);

        foreach ($accessTokens as $accessToken) {
            $this->entityManager->remove($accessToken);
        }
        $this->entityManager->flush();

        return $this->redirect()->toRoute('adminaut/auth/logout');
    }
}
