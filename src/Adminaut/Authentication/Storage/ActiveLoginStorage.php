<?php

namespace Adminaut\Authentication\Storage;

use Adminaut\Authentication\Exception\Exception;
use Adminaut\Authentication\Helper\AccessTokenHelper;
use Adminaut\Entity\UserActiveLoginEntity;
use Adminaut\Entity\UserEntity;
use Adminaut\Repository\UserActiveLoginRepository;
use Adminaut\Repository\UserRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class ActiveLoginStorage
 * @package Adminaut\Authentication\Storage
 */
class ActiveLoginStorage implements StorageInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var StorageInterface
     */
    private $accessTokenStorage;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserActiveLoginRepository
     */
    private $userActiveLoginRepository;

    /**
     * For cache purposes.
     * @var UserEntity
     */
    private $resolvedUserEntity;

    //-------------------------------------------------------------------------

    /**
     * ActiveLoginStorage constructor.
     * @param EntityManager $entityManager
     * @param StorageInterface $accessTokenStorage
     */
    public function __construct(EntityManager $entityManager, StorageInterface $accessTokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->accessTokenStorage = $accessTokenStorage;
    }

    /**
     * @return StorageInterface
     */
    public function getAccessTokenStorage()
    {
        return $this->accessTokenStorage;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        if (null === $this->userRepository) {
            $this->userRepository = $this->entityManager->getRepository(UserEntity::class);
        }
        return $this->userRepository;
    }

    /**
     * @return UserActiveLoginRepository
     */
    public function getUserActiveLoginRepository()
    {
        if (null === $this->userActiveLoginRepository) {
            $this->userActiveLoginRepository = $this->entityManager->getRepository(UserActiveLoginEntity::class);
        }
        return $this->userActiveLoginRepository;
    }

    //-------------------------------------------------------------------------

    /**
     * Returns true if and only if storage is empty
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If it is impossible to determine whether storage is empty
     * @return bool
     */
    public function isEmpty()
    {
        // If access token storage is empty...
        if (true === $this->getAccessTokenStorage()->isEmpty()) {

            // Set resolved user entity to null.
            $this->resolvedUserEntity = null;

            // Return true.
            return true;
        } else if (null === $this->resolvedUserEntity) {
            $this->resolvedUserEntity = $this->read();
        }


        // If resolved user entity is null or resolved user entity is not active...
        if (null === $this->resolvedUserEntity || false === $this->resolvedUserEntity->isActive()) {

            // Clear access token storage.
            $this->getAccessTokenStorage()->clear();

            // Set resolved user entity to null.
            $this->resolvedUserEntity = null;

            // Return true.
            return true;
        }

        // Return false.
        return false;
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If reading contents from storage is impossible
     * @return mixed
     */
    public function read()
    {
        if (null === $this->resolvedUserEntity) {
            if (false === $this->getAccessTokenStorage()->isEmpty()) {
                $accessToken = $this->getAccessTokenStorage()->read();

                $accessTokenHash = AccessTokenHelper::hash($accessToken);

                $activeLogin = $this->getUserActiveLoginRepository()->findOneByAccessTokenHash($accessTokenHash);

                if ($activeLogin instanceof UserActiveLoginEntity) {
                    $this->resolvedUserEntity = $activeLogin->getUser();
                }
            }
        }

        return $this->resolvedUserEntity;
    }

    /**
     * Writes $contents to storage
     *
     * @param  UserEntity $contents
     * @throws \Zend\Authentication\Exception\ExceptionInterface If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        if ($contents instanceof UserEntity) {
            try {
                $accessToken = AccessTokenHelper::generate();

                $this->getAccessTokenStorage()->clear();
                $this->getAccessTokenStorage()->write($accessToken);

                $accessTokenHash = AccessTokenHelper::hash($accessToken);

                $activeLogin = new UserActiveLoginEntity($contents, $accessTokenHash);

                $this->entityManager->persist($activeLogin);
                $this->entityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            } catch (\Exception $exception) {
                throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
            }
        }
    }

    /**
     * Clears contents from storage
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        if (false === $this->getAccessTokenStorage()->isEmpty()) {

            $accessToken = $this->accessTokenStorage->read();

            $accessTokenHash = AccessTokenHelper::hash($accessToken);

            $activeLogin = $this->getUserActiveLoginRepository()->findOneByAccessTokenHash($accessTokenHash);

            if ($activeLogin && $activeLogin instanceof UserActiveLoginEntity) {
                try {
                    $this->entityManager->remove($activeLogin);
                    $this->entityManager->flush();
                } catch (\Exception $exception) {
                    //
                }
            }

            $this->accessTokenStorage->clear();
        }
    }
}
