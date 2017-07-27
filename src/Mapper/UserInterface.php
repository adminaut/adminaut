<?php

namespace Adminaut\Mapper;

/**
 * Interface UserInterface
 * @package Adminaut\Mapper
 */
interface UserInterface
{
    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email);

    /**
     * @param $username
     * @return mixed
     */
    public function findByUsername($username);

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param $user
     * @return mixed
     */
    public function insert($user);

    /**
     * @param $user
     * @return mixed
     */
    public function update($user);
}