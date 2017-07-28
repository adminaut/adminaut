<?php

namespace Adminaut\Mapper;

/**
 * Interface UserMapperInterface
 * @package Adminaut\Mapper
 */
interface UserMapperInterface
{

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param string $email
     * @return mixed
     */
    public function findByEmail($email);

    /**
     * @param object $entity
     * @return mixed
     */
    public function insert($entity);

    /**
     * @param object $entity
     * @return mixed
     */
    public function update($entity);
}
