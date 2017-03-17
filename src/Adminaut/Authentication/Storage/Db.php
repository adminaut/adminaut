<?php
namespace Adminaut\Authentication\Storage;

use Adminaut\Mapper\UserMapper;
use Zend\Authentication\Storage;
use Zend\Authentication\Storage\StorageInterface;

/**
 * Class Db
 * @package Adminaut\Authentication\Storage
 */
class Db implements Storage\StorageInterface
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var mixed
     */
    protected $resolvedIdentity;

    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * Db constructor.
     * @param UserMapper $userMapper
     */
    public function __construct(UserMapper $userMapper)
    {
        $this->setUserMapper($userMapper);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        if ($this->getStorage()->isEmpty()) {
            return true;
        }
        $identity = $this->read();
        if ($identity === null) {
            $this->clear();
            return true;
        }
        return false;
    }

    /**
     * @return mixed|null|object
     */
    public function read()
    {
        if (null !== $this->resolvedIdentity) {
            return $this->resolvedIdentity;
        }
        $identity = $this->getStorage()->read();
        if (is_int($identity) || is_scalar($identity)) {
            $identity = $this->getUserMapper()->findById($identity);
        }
        if ($identity) {
            $this->resolvedIdentity = $identity;
        } else {
            $this->resolvedIdentity = null;
        }
        return $this->resolvedIdentity;
    }

    /**
     * @param mixed $contents
     */
    public function write($contents)
    {
        $this->resolvedIdentity = null;
        $this->getStorage()->write($contents);
    }

    /**
     *
     */
    public function clear()
    {
        $this->resolvedIdentity = null;
        $this->getStorage()->clear();
    }

    /**
     * @return StorageInterface
     */
    public function getStorage()
    {
        if (null === $this->storage) {
            $this->setStorage(new Storage\Session);
        }
        return $this->storage;
    }

    /**
     * @param StorageInterface $storage
     * @return $this
     */
    public function setStorage(Storage\StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return UserMapper
     */
    public function getUserMapper()
    {
        return $this->userMapper;
    }

    /**
     * @param UserMapper $userMapper
     */
    public function setUserMapper($userMapper)
    {
        $this->userMapper = $userMapper;
    }
}