<?php

namespace Adminaut\Authentication\Adapter;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Entity\UserEntity;
use Adminaut\Entity\UserEntityInterface;
use Adminaut\Entity\UserLoginEntity;
use Adminaut\Options\AuthAdapterOptions;
use Adminaut\Repository\UserLoginRepository;
use Adminaut\Repository\UserRepository;
use Adminaut\Service\MailServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Class AuthAdapter
 * @package Adminaut\Authentication\Adapter
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var AuthAdapterOptions
     */
    private $options;

    /**
     * @var MailServiceInterface
     */
    private $mailService;

    //-------------------------------------------------------------------------

    /**
     * AuthAdapter constructor.
     * @param EntityManager $entityManager
     * @param AuthAdapterOptions $options
     * @param MailServiceInterface $mailService
     */
    public function __construct(EntityManager $entityManager, AuthAdapterOptions $options, MailServiceInterface $mailService)
    {
        $this->entityManager = $entityManager;
        $this->options = $options;
        $this->mailService = $mailService;
    }

    //-------------------------------------------------------------------------

    /**
     * Performs an authentication attempt
     *
     * @param string $email
     * @param string $password
     * @return Result
     */
    public function authenticate($email = null, $password = null)
    {
        // Get user by email
        $user = $this->getUserByEmail($email);

        // If user is null...
        if (null === $user) {
            return $this->getResult(Result::FAILURE_IDENTITY_NOT_FOUND, _('Invalid credentials.'));
        }

        // If user is not active...
        if (false === $user->isActive()) { // todo: change to getStatus with STATUS_ACTIVE code
            return $this->getResult(Result::FAILURE, _('Invalid credentials.'));
        }

        if (true === $this->options->isAutomaticUnlockDisabled() && $user->getStatus() === UserEntity::STATUS_LOCKED) {
            return $this->getResult(Result::FAILURE, sprintf(_('Account has been locked.')));
        }

        $failedLogins = $this->getFailedLoginsByUser($user);

        if ($this->options->getFailedAttemptsToLock() <= count($failedLogins)) {

            if (true === $this->options->isAutomaticUnlockDisabled()) {
                $user->setStatus(UserEntity::STATUS_LOCKED);
                $this->entityManager->flush($user);

                $_message = _('Account has been locked.');
                $this->mailService->sendNotificationMail($_message, $user->getEmail(), $user->getName());
                return $this->getResult(Result::FAILURE, $_message);
            }

            /** @var UserLoginEntity $lastFailedLogin */
            $lastFailedLogin = end($failedLogins);

            $nowDT = new DateTime();
            $unlockDT = $this->getUnlockDateTime($lastFailedLogin);

            if ($nowDT < $unlockDT) {
                $_message = sprintf(_('Account has been locked until %s.'), $unlockDT->format('Y-m-d H:i:s'));
                $this->mailService->sendNotificationMail($_message, $user->getEmail(), $user->getName());
                return $this->getResult(Result::FAILURE, $_message);
            }
        }

        if (true !== PasswordHelper::verify($password, $user->getPassword())) {
            $this->addFailedLogin($user);
            return $this->getResult(Result::FAILURE_CREDENTIAL_INVALID, _('Invalid credentials.'));
        }

        $this->addSuccessfulLogin($user);
        $this->deactivateFailedLoginsByUser($user);

        return $this->getResult(Result::SUCCESS, _('Authenticated successfully.'), $user);
    }

    /**
     * @param UserEntityInterface $user
     * @param $password
     * @return Result
     */
    public function changePassword($user, $password)
    {
        try {
            $newPasswordHash = PasswordHelper::hash($password);

            $user->setPassword($newPasswordHash);
            $user->setPasswordChangeOnNextLogon(false);
            $this->entityManager->flush($user);
            return $this->getResult(Result::SUCCESS, _('Password changed successfully.'), $user);
        } catch (\Exception $e) {
            return $this->getResult(Result::FAILURE, _('Cannot change password, try again later.'));
        }
    }

    //-------------------------------------------------------------------------

    /**
     * @return EntityRepository|UserRepository
     */
    private function getUserRepository()
    {
        return $this->entityManager->getRepository(UserEntity::class);
    }

    /**
     * @return EntityRepository|UserLoginRepository
     */
    private function getUserLoginRepository()
    {
        return $this->entityManager->getRepository(UserLoginEntity::class);
    }

    /**
     * @param UserEntity $userEntity
     * @return UserLoginEntity[]|array
     */
    private function getFailedLoginsByUser(UserEntity $userEntity)
    {
        return $this->getUserLoginRepository()->findActiveFailedByUser($userEntity);
    }

    /**
     * @param string $email
     * @return UserEntity|null|object
     */
    private function getUserByEmail($email)
    {
        return $this->getUserRepository()->findOneByEmail($email);
    }

    /**
     * @param UserEntity $userEntity
     */
    private function addFailedLogin(UserEntity $userEntity)
    {
        $login = new UserLoginEntity($userEntity, UserLoginEntity::TYPE_FAILED);

        $this->entityManager->persist($login);
        $this->entityManager->flush();
    }

    /**
     * @param UserEntity $userEntity
     */
    private function addSuccessfulLogin(UserEntity $userEntity)
    {
        $login = new UserLoginEntity($userEntity, UserLoginEntity::TYPE_SUCCESSFUL);

        $this->entityManager->persist($login);
        $this->entityManager->flush();
    }

    /**
     * @param UserEntity $userEntity
     */
    private function deactivateFailedLoginsByUser(UserEntity $userEntity)
    {
        foreach ($this->getFailedLoginsByUser($userEntity) as $failedLogin) {
            $failedLogin->setActive(false);
        }
        $this->entityManager->flush();
    }

    /**
     * @param int $code
     * @param string $message
     * @param UserEntity|null $identity
     * @return Result
     */
    private function getResult($code, $message, UserEntity $identity = null)
    {
        $messages = [];
        $messages[] = $message;
        return new Result($code, $identity, $messages);
    }

    /**
     * @param UserLoginEntity $loginEntity
     * @return DateTime
     */
    private function getUnlockDateTime(UserLoginEntity $loginEntity)
    {
        // cannot just assign datetime from login entity, it will update database record!
        $unlockDateTime = new DateTime($loginEntity->getInserted()->format('Y-m-d H:i:s'));

        $unlockDateTime->add(new \DateInterval(sprintf('PT%sS', $this->options->getSecondsToUnlock())));

        return $unlockDateTime;
    }
}
