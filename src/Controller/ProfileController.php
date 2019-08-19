<?php

namespace Adminaut\Controller;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Entity\UserAccessTokenEntity;
use Adminaut\Entity\UserLoginEntity;
use Adminaut\Form\InputFilter\UserChangePasswordInputFilter;
use Adminaut\Form\InputFilter\UserSettingsInputFilter;
use Adminaut\Form\UserChangePasswordForm;
use Adminaut\Form\UserSettingsForm;
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
    const ROUTE_SETTINGS = 'adminaut/profile/settings';
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
        $user = $this->authentication()->getIdentity();

        return new ViewModel([
            'user' => $user,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function settingsAction()
    {
        $form = new UserSettingsForm();
        $form->setInputFilter(new UserSettingsInputFilter());

        $user = $this->authentication()->getIdentity();

        $form->setData([
            'name' => $user->getName(),
            'language' => $user->getLanguage(),
        ]);

        /** @var Request $request */
        $request = $this->getRequest();

        if (true === $request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $formData = $form->getData();

                try {

                    $user->setName($formData['name']);
                    $user->setLanguage($formData['language']);

                    $this->entityManager->flush($user);

                    $this->addSuccessMessage($this->translate('Settings have been changed.', 'adminaut'));

                    return $this->redirect()->toRoute(self::ROUTE_SETTINGS);
                } catch (\Exception $e) {
                    $this->addErrorMessage($this->translate('Setting could not be changed.', 'adminaut'));
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
    public function changePasswordAction()
    {
        $form = new UserChangePasswordForm();
        $form->setInputFilter(new UserChangePasswordInputFilter());

        $user = $this->authentication()->getIdentity();

        /** @var Request $request */
        $request = $this->getRequest();

        if (true === $request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $formData = $form->getData();

                if (true === PasswordHelper::verify($formData['password'], $user->getPassword())) {

                    try {

                        $newPassword = $formData['newPassword'];
                        $newPasswordHash = PasswordHelper::hash($newPassword);

                        $user->setPassword($newPasswordHash);
                        $this->entityManager->flush($user);

                        $this->addSuccessMessage($this->translate('Password has been changed.', 'adminaut'));

                        return $this->redirect()->toRoute(self::ROUTE_CHANGE_PASSWORD);
                    } catch (\Exception $exception) {
                        $this->addErrorMessage($this->translate('Password could not be changed.', 'adminaut'));
                    }
                } else {
                    $this->addWarningMessage($this->translate('Password does not match current password.', 'adminaut'));
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
        $user = $this->authentication()->getIdentity();

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
        $user = $this->authentication()->getIdentity();

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
        $user = $this->authentication()->getIdentity();
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
        $user = $this->authentication()->getIdentity();

        /** @var UserAccessTokenRepository $atr */
        $atr = $this->entityManager->getRepository(UserAccessTokenEntity::class);
        $accessTokens = $atr->findBy(['user' => $user]);

        foreach ($accessTokens as $accessToken) {
            $this->entityManager->remove($accessToken);
        }
        $this->entityManager->flush();

        return $this->redirect()->toRoute(AuthController::ROUTE_LOGOUT);
    }
}
