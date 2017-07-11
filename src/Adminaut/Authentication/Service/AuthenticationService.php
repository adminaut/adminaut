<?php

namespace Adminaut\Authentication\Service;

use Adminaut\Authentication\Adapter\AuthAdapterInterface;
use Adminaut\Authentication\Helper\AccessTokenHelper;
use Adminaut\Entity\UserActiveLoginEntity;
use Adminaut\Entity\UserEntity;
use Adminaut\Repository\UserActiveLoginRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\StorageInterface;

/**
 * Class AuthenticationService
 * @package Adminaut\Authentication\Service
 */
class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var AuthAdapterInterface
     */
    private $adapter;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var UserEntity
     */
    private $resolvedIdentity;

    //-------------------------------------------------------------------------

    /**
     * AuthenticationService constructor.
     * @param EntityManager $entityManager
     * @param AuthAdapterInterface $adapter
     * @param StorageInterface $storage
     */
    public function __construct(EntityManager $entityManager, AuthAdapterInterface $adapter, StorageInterface $storage)
    {
        $this->entityManager = $entityManager;
        $this->adapter = $adapter;
        $this->storage = $storage;
    }

    //-------------------------------------------------------------------------

    /**
     * @return AuthAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param AuthAdapterInterface $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param StorageInterface $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    //-------------------------------------------------------------------------

    /**
     * Authenticates and provides an authentication result
     * @return Result
     * @throws Exception
     */
    public function authenticate()
    {
        //return $this->adapter->authenticate();

        $this->adapter->setEmail($this->email);
        $this->adapter->setPassword($this->password);

        $result = $this->adapter->authenticate();

        if ($result->getCode() !== Result::SUCCESS || true !== $result->getIdentity() instanceof UserEntity) {
            return $result;
        }

        $accessToken = AccessTokenHelper::generate();

        $this->getStorage()->write($accessToken);

        $accessTokenHash = AccessTokenHelper::hash($accessToken);

        $activeLogin = new UserActiveLoginEntity($result->getIdentity(), $accessTokenHash);

        $this->entityManager->persist($activeLogin);
        $this->entityManager->flush();

        return $result;
    }

    /**
     * Returns true if and only if an identity is available
     *
     * @return bool
     */
    public function hasIdentity()
    {
        if (false !== $this->storage->isEmpty()) {
            return false;
        }

        if (null === $this->resolvedIdentity) {
            $accessToken = $this->storage->read();

            $accessTokenHash = AccessTokenHelper::hash($accessToken);

            $login = $this->getUserActiveLoginRepository()->findOneByAccessTokenHash($accessTokenHash);

            if (null !== $login && true === $login instanceof UserActiveLoginEntity) {
                $this->resolvedIdentity = $login->getUser();
            }
        }

        if (null === $this->resolvedIdentity || true !== $this->resolvedIdentity->isActive()) {
            $this->storage->clear();
            return false;
        }

        return true;
    }

    /**
     * Returns the authenticated identity or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        if (true === $this->hasIdentity()) {
            return $this->resolvedIdentity;
        }

        return null;
    }

    /**
     * Clears the identity
     *
     * @return void
     */
    public function clearIdentity()
    {

        if (true !== $this->hasIdentity()) {
            return;
        }

        $accessToken = $this->storage->read();
        $accessTokenHash = AccessTokenHelper::hash($accessToken);

        $activeLogin = $this->getUserActiveLoginRepository()->findOneByAccessTokenHash($accessTokenHash);

        $this->entityManager->remove($activeLogin);

        $this->entityManager->flush();

        $this->storage->clear();
    }

    //-------------------------------------------------------------------------

    /**
     * @return EntityRepository|UserActiveLoginRepository
     */
    private function getUserActiveLoginRepository()
    {
        return $this->entityManager->getRepository(UserActiveLoginEntity::class);
    }
}
