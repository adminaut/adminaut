<?php

namespace Adminaut\Authentication\Adapter;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Entity\UserEntity;
use Adminaut\Entity\UserLoginEntity;
use Adminaut\Repository\UserLoginRepository;
use Adminaut\Repository\UserRepository;
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
     * @var int
     */
    private $failedLoginCount = 3;

    /**
     * @var int
     */
    private $failedLoginTimeout = 30; // 30 seconds

    //-------------------------------------------------------------------------

    /**
     * AuthAdapter constructor.
     * @param EntityManager $entityManager
     * @param array $options
     */
    public function __construct(EntityManager $entityManager, array $options = [])
    {
        $this->entityManager = $entityManager;
        // todo: add options
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
            return $this->getResult(Result::FAILURE_IDENTITY_NOT_FOUND, _('Account does not exist.'));
        }

        // If use is not active...
        if (false === $user->isActive()) {
            return $this->getResult(Result::FAILURE, _('Account is not active.'));
        }

        $failedLogins = $this->getFailedLoginsByUser($user);

        if ($this->failedLoginCount <= count($failedLogins)) {

            /** @var UserLoginEntity $lastFailedLogin */
            $lastFailedLogin = end($failedLogins);

            $since = new \DateTime();
            $since->sub(new \DateInterval(sprintf('PT%sS', $this->failedLoginTimeout)));

            if ($lastFailedLogin->getInserted()->getTimestamp() > $since->getTimestamp()) {
                $timeToWait = $lastFailedLogin->getInserted()->diff($since)->s;
                return $this->getResult(Result::FAILURE, sprintf(_('You have to wait for %s seconds.'), $timeToWait));
            }
        }

        if (true !== PasswordHelper::verify($password, $user->getPassword())) {
            $this->addFailedLogin($user);
            return $this->getResult(Result::FAILURE_CREDENTIAL_INVALID, _('Invalid password.'));
        }

        $this->addSuccessfulLogin($user);
        $this->deactivateFailedLoginsByUser($user);

        return $this->getResult(Result::SUCCESS, _('Authenticated successfully.'), $user);
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
}
