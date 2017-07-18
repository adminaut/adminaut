<?php

namespace Adminaut\Authentication\Service;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Authentication\Adapter\AuthAdapterInterface;
use Adminaut\Authentication\Exception\Exception;
use Adminaut\Authentication\Storage\StorageInterface;
use Adminaut\Entity\UserEntity;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;

/**
 * Class AuthenticationService
 * @package Adminaut\Authentication\Service
 */
class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * @var AuthAdapterInterface
     */
    private $adapter;

    /**
     * @var StorageInterface
     */
    private $storage;

    //-------------------------------------------------------------------------

    /**
     * AuthenticationService constructor.
     * @param AuthAdapter $adapter
     * @param StorageInterface $storage
     */
    public function __construct(AuthAdapter $adapter, StorageInterface $storage)
    {
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

    //-------------------------------------------------------------------------

    /**
     * Authenticates and provides an authentication result
     * @return Result
     * @throws Exception
     */
    public function authenticate()
    {
        $result = $this->adapter->authenticate();

        if ($result->getCode() === Result::SUCCESS && true === $result->getIdentity() instanceof UserEntity) {
            $this->storage->write($result->getIdentity());
        }

        return $result;
    }

    /**
     * Returns true if and only if an identity is available
     *
     * @return bool
     */
    public function hasIdentity()
    {
        if (true === $this->storage->isEmpty()){
            return false;
        }
        return true;
    }

    /**
     * Returns the authenticated identity or null if no identity is available
     *
     * @return UserEntity|null
     */
    public function getIdentity()
    {
        if (false === $this->storage->isEmpty()) {
            return $this->storage->read();
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
        if (false === $this->storage->isEmpty()) {
            $this->storage->clear();
        }
    }
}
