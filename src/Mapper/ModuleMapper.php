<?php

namespace Adminaut\Mapper;

use Doctrine\ORM\EntityManager;
use Adminaut\Options\ModuleOptions;

/**
 * Class ModuleMapper
 * @package Adminaut\Mapper
 */
class ModuleMapper extends AbstractMapper
{

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * ModuleMapper constructor.
     * @param EntityManager $entityManager
     * @param ModuleOptions $moduleOptions
     */
    public function __construct(EntityManager $entityManager, ModuleOptions $moduleOptions)
    {
        parent::__construct($entityManager);
        $this->options = $moduleOptions;
    }

    /**
     * @param array|null $criteria
     * @return array
     */
    public function getList(array $criteria = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from($this->options->getEntityClass(), 'e')
            ->where('e.deleted = 0')
            ->orderBy('e.id', 'ASC');
        if ($criteria) {
            foreach (array_keys($criteria) as $property) {
                $qb->andWhere('e.' . $property . ' = :' . $property);
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
        $er = $this->getEntityManager()->getRepository($this->options->getEntityClass());
        return $er->findOneBy([
            'id' => $id,
            'deleted' => 0,
        ]);
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function insert($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function update($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}
