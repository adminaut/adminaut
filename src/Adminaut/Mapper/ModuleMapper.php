<?php

namespace Adminaut\Mapper;

use Doctrine\ORM\EntityManagerInterface;

use Adminaut\Options\ModuleOptions as ModuleOptions;


/**
 * Class ModuleMapper
 * @package Adminaut\Mapper
 */
class ModuleMapper
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * ModuleMapper constructor.
     * @param EntityManagerInterface $em
     * @param ModuleOptions $options
     */
    public function __construct(EntityManagerInterface $em, ModuleOptions $options)
    {
        $this->em = $em;
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getList($criteria = null)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e')
            ->from($this->options->getEntityClass(), 'e')
            ->where('e.deleted = 0')
            ->orderBy('e.id', 'ASC');
        if($criteria) {
            foreach(array_keys($criteria) as $property) {
                $qb->andWhere('e.' . $property. ' = :' . $property);
                $qb->setParameter($property, $criteria[$property]);
            }
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->em->getRepository($this->options->getEntityClass());
        return $er->findOneBy([
            'id' => $id,
            'deleted' => 0
        ]);
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function insert($entity)
    {
        return $this->persist($entity);
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function update($entity)
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