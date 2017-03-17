<?php

namespace Adminaut\Entity;

/**
 * Interface UserInterface
 * @package Adminaut\Entity
 */
interface UserInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

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
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     */
    public function setStatus($status);
}