<?php

namespace Adminaut\Entity;

/**
 * Interface UserEntityInterface
 * @package Adminaut\Entity
 */
interface UserEntityInterface extends AdminautEntityInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getRole();

    /**
     * @param string $role
     */
    public function setRole($role);

    /**
     * @return string
     */
    public function getLanguage();

    /**
     * @param string $language
     */
    public function setLanguage($language);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     */
    public function setStatus($status);
}
