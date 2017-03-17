<?php
namespace Adminaut\Authentication\Adapter;

use Zend\Authentication\Storage;

/**
 * Class AbstractAdapter
 * @package Adminaut\Authentication\Adapter
 */
abstract class AbstractAdapter implements ChainableAdapter
{
    /**
     * @var Storage\StorageInterface
     */
    protected $storage;

    /**
     * @return Storage\StorageInterface
     */
    public function getStorage()
    {
        if (null === $this->storage) {
            $this->setStorage(new Storage\Session(get_class($this)));
        }

        return $this->storage;
    }

    /**
     * @param Storage\StorageInterface $storage
     * @return $this
     */
    public function setStorage(Storage\StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSatisfied()
    {
        $storage = $this->getStorage()->read();
        return (isset($storage['is_satisfied']) && true === $storage['is_satisfied']);
    }

    /**
     * @param bool|true $bool
     * @return AbstractAdapter
     */
    public function setSatisfied($bool = true)
    {
        $storage = $this->getStorage()->read() ?: array();
        $storage['is_satisfied'] = $bool;
        $this->getStorage()->write($storage);
        return $this;
    }
}