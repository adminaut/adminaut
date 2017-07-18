<?php

namespace Adminaut\Mapper;

use Adminaut\Options\UserOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class UserMapper
 * @package Adminaut\Mapper
 */
class UserMapper extends AbstractDbMapper
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var UserOptions
     */
    protected $options;

    /**
     * User constructor.
     * @param $em
     * @param $options
     */
    public function __construct($em, $options)
    {
        $this->em = $em;
        $this->options = $options;
    }

    /**
     * @param $email
     * @return object
     */
    public function findByEmail($email)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        return $er->findOneBy(['email' => $email]);
    }

    /**
     * @param $username
     * @return object
     */
    public function findByUsername($username)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        return $er->findOneBy(['username' => $username]);
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        return $er->findOneBy([
            'id' => $id,
            'deleted' => 0,
        ]);
    }

    public function findFirst()
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        return (isset($er->findAll()[0])) ? $er->findAll()[0] : [];
    }

    /**
     * @param array|object $entity
     * @param null $tableName
     * @param HydratorInterface|null $hydrator
     * @return mixed
     */
    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    /**
     * @param $entity
     * @param null $where
     * @param null $tableName
     * @param HydratorInterface|null $hydrator
     * @return mixed
     */
    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    /**
     * @param $entity
     * @return mixed
     */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
}