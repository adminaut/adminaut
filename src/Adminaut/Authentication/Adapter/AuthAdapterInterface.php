<?php

namespace Adminaut\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;

/**
 * Interface AuthAdapterInterface
 * @package Adminaut\Authentication\Adapter
 */
interface AuthAdapterInterface extends AdapterInterface
{
    /**
     * @param string $email
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $password
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getPassword();
}
