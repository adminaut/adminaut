<?php

namespace Adminaut\Authentication\Storage;

use Adminaut\Authentication\Exception\Exception;
use Adminaut\Authentication\Helper\AccessTokenHelper;
use Adminaut\Entity\UserAccessTokenEntity;
use Adminaut\Entity\UserEntity;
use Adminaut\Repository\UserAccessTokenRepository;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\Storage\StorageInterface;

/**
 * Class AuthStorage
 * @package Adminaut\Authentication\Storage
 */
class AuthStorage implements StorageInterface
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
     * @var UserAccessTokenRepository
     */
    private $userAccessTokenRepository;

    /**
     * @var UserEntity
     */
    private $resolvedUserEntity;

    //-------------------------------------------------------------------------

    /**
     * AuthStorage constructor.
     * @param EntityManager $entityManager
     * @param StorageInterface $accessTokenStorage
     */
    public function __construct(EntityManager $entityManager, StorageInterface $accessTokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->accessTokenStorage = $accessTokenStorage;
    }

    /**
     * @return UserAccessTokenRepository
     */
    public function getUserAccessTokenRepository()
    {
        if (null === $this->userAccessTokenRepository) {
            $this->userAccessTokenRepository = $this->entityManager->getRepository(UserAccessTokenEntity::class);
        }
        return $this->userAccessTokenRepository;
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
        if (true === $this->accessTokenStorage->isEmpty()) {

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
            $this->accessTokenStorage->clear();

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
            if (false === $this->accessTokenStorage->isEmpty()) {
                $accessToken = $this->accessTokenStorage->read();

                $accessTokenHash = AccessTokenHelper::hash($accessToken);

                $activeLogin = $this->getUserAccessTokenRepository()->findOneByHash($accessTokenHash);

                if ($activeLogin instanceof UserAccessTokenEntity) {
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

                $this->accessTokenStorage->clear();
                $this->accessTokenStorage->write($accessToken);

                $accessTokenHash = AccessTokenHelper::hash($accessToken);

                $activeLogin = new UserAccessTokenEntity($contents, $accessTokenHash);

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
        if (false === $this->accessTokenStorage->isEmpty()) {

            $accessToken = $this->accessTokenStorage->read();

            $accessTokenHash = AccessTokenHelper::hash($accessToken);

            $activeLogin = $this->getUserAccessTokenRepository()->findOneByHash($accessTokenHash);

            if ($activeLogin && $activeLogin instanceof UserAccessTokenEntity) {
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
