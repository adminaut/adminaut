<?php

namespace Adminaut\Manager;

use Adminaut\Datatype\MultiReference;
use Adminaut\Datatype\Reference;
use Adminaut\Form\Annotation\AnnotationBuilder;
use Adminaut\Form\Element\CyclicSheet;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use DoctrineModule\Form\Element\ObjectRadio;
use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Entity\UserEntityInterface;
use Adminaut\Options\ModuleOptions;
use Adminaut\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;

/**
 * Class ModuleManager
 * @package Adminaut\Manager
 */
class ModuleManager extends AManager
{

    /**
     * @var array
     */
    private $modules;

    //-------------------------------------------------------------------------

    /**
     * ModuleManager constructor.
     * @param EntityManager $entityManager
     * @param array $modules
     */
    public function __construct(EntityManager $entityManager, array $modules = [])
    {
        parent::__construct($entityManager);
        $this->modules = $modules;
    }

    //-------------------------------------------------------------------------

    /**
     * @param string $entityName
     * @return EntityRepository
     */
    private function getRepository($entityName)
    {
        return $this->entityManager->getRepository((string)$entityName);
    }

    //-------------------------------------------------------------------------

    /**
     * @param $entityName
     * @param array|null $orderBy
     * @return array
     */
    public function findAll($entityName, array $orderBy = null)
    {

        if (null === $orderBy) {
            $orderBy = ['id' => 'ASC'];
        }

        $repository = $this->getRepository($entityName);

        return $repository->findBy(['deleted' => false], $orderBy);
    }

    /**
     * @param $entityName
     * @param array $criteria
     * @param array|null $orderBy
     * @return array
     */
    public function findBy($entityName, array $criteria = [], array $orderBy = null)
    {
        $criteria = array_merge(['deleted' => false], $criteria);

        if (null === $orderBy) {
            $orderBy = ['id' => 'ASC'];
        }

        $repository = $this->getRepository($entityName);

        $joined = [];
        $qb = $repository->createQueryBuilder('e');

        foreach ($criteria as $criterionField => $criterionValue) {
            if(strpos($criterionField, '.')) {
                $join = "";
                $joinAlias = "";
                foreach ($a = explode('.', $criterionField) as $x) {
                    if($x === end($a)) { break; }

                    $join .= (!empty($join) ? '.' : '') . $x;
                    $joinAlias = str_replace('.', '_', $join);

                    if(!in_array($join, $joined)) {
                        $qb->join('e.' . $join, 'e_' . $joinAlias);
                        $joined[] = $join;
                    }
                }

                $qb->andWhere("e_$joinAlias.$x = :e_$joinAlias_$x");
                $qb->setParameter("e_$joinAlias_$x", $criterionValue);
            } else {
                $qb->andWhere("e.$criterionField = :e_$criterionField");
                $qb->setParameter("e_$criterionField", $criterionValue);
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $entityName
     * @param array $criteria
     * @return null|object
     */
    public function findOneBy($entityName, array $criteria = [])
    {
        $criteria = array_merge(['deleted' => false], $criteria);
        $repository = $this->getRepository($entityName);

        $joined = [];
        $qb = $repository->createQueryBuilder('e');

        foreach ($criteria as $criterionField => $criterionValue) {
            if(strpos($criterionField, '.')) {
                $join = "";
                $joinAlias = "";
                foreach ($a = explode('.', $criterionField) as $x) {
                    if($x === end($a)) { break; }

                    $join .= (!empty($join) ? '.' : '') . $x;
                    $joinAlias = str_replace('.', '_', $join);

                    if(!in_array($join, $joined)) {
                        $qb->join('e.' . $join, 'e_' . $joinAlias);
                        $joined[] = $join;
                    }
                }

                $qb->andWhere("e_$joinAlias.$x = :e_$joinAlias_$x");
                $qb->setParameter("e_$joinAlias_$x", $criterionValue);
            } else {
                $qb->andWhere("e.$criterionField = :e_$criterionField");
                $qb->setParameter("e_$criterionField", $criterionValue);
            }
        }

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $entityName
     * @param $id
     * @return null|object
     */
    public function findOneById($entityName, $id)
    {
        $repository = $this->getRepository($entityName);

        return $repository->findOneBy([
            'id' => (int)$id,
            'deleted' => false,
        ]);
    }

    /**
     * @param $entityName
     * @param Form $form
     * @param AdminautEntityInterface|null $parentEntity
     * @param UserEntityInterface|null $admin
     * @return AdminautEntityInterface
     */
    public function create($entityName, Form $form, AdminautEntityInterface $parentEntity = null, UserEntityInterface $admin = null)
    {
        /* @var $entity AdminautEntityInterface */
        $entity = new $entityName();

        $entity = $this->bind($entity, $form, $parentEntity);

        if ($admin) {
            $entity->setInsertedBy($admin->getId());
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param AdminautEntityInterface $entity
     * @param Form $form
     * @param AdminautEntityInterface|null $parentEntity
     * @param UserEntityInterface|null $admin
     * @return AdminautEntityInterface
     */
    public function update(AdminautEntityInterface $entity, Form $form, AdminautEntityInterface $parentEntity = null, UserEntityInterface $admin = null)
    {
        $entity = $this->bind($entity, $form, $parentEntity);

        if ($admin) {
            $entity->setUpdatedBy($admin->getId());
        }

        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param AdminautEntityInterface $entity
     * @param UserEntityInterface|null $admin
     * @return AdminautEntityInterface
     */
    public function delete(AdminautEntityInterface $entity, UserEntityInterface $admin = null)
    {
        $entity->setDeleted(true);

        if ($admin instanceof UserEntityInterface) {
            $entity->setDeletedBy($admin->getId());
        }

        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param ModuleOptions $moduleOptions
     * @return Form
     */
    public function createForm(ModuleOptions $moduleOptions)
    {
        $entityName = $moduleOptions->getEntityClass();
        $builder = new AnnotationBuilder();

        /** @var Form $form */
        $form = $builder->createForm(new $entityName());
        $form->setHydrator(new DoctrineObject($this->entityManager));

        if (isset($moduleOptions->getLabels()['general_tab'])) {
            $tabs = $form->getTabs();
            $tabs['main']['label'] = $moduleOptions->getLabels()['general_tab'];
            $form->setTabs($tabs);
        }

        /** @var Fieldset[] $fieldsets */
//        $fieldsets = [];

        /** @var ObjectSelect|ObjectRadio|ObjectMultiCheckbox|CyclicSheet $element */
        foreach ($form->getElements() as $element) {
            if ($element instanceof ObjectSelect ||
                $element instanceof ObjectRadio ||
                $element instanceof ObjectMultiCheckbox ||
                $element instanceof Reference ||
                $element instanceof MultiReference) {
                $element->setOption('object_manager', $this->entityManager);
            } else if ($element instanceof CyclicSheet) {
                $form->addTab($element->getName(), [
                    'label' => $element->getLabel(),
                    'action' => 'cyclicSheetAction',
                    'entity' => $element->getTargetClass(),
                    'referencedProperty' => $element->getReferencedProperty(),
                    'readonly' => $element->isReadonly(),
                    'active' => false,
                ]);

                $form->remove($element->getName());
                continue;
            }

            if (method_exists($element, 'isPrimary')) {
                if ($element->isPrimary()) {
                    $form->setPrimaryField($element->getName());
                }
            } else if ($element->getOption('primary') === true) {
                $form->setPrimaryField($element->getName());
            }


            /*if($tab = $element->getOption("tab")) {
                if($tab != "General") {
                    $filter = new \Zend\Filter\StripTags();
                    $tabName = $filter->filter($tab);

                    if(!isset($fieldsets[$tab])) {
                        $fieldsets[$tab] = new Fieldset($tab);
                    }

                    $fieldsets[$tab]->add($element);
                    $form->addTab($tab, [
                        'label' => $tab,
                        'action' => 'formTab'
                    ]);
                    $form->remove($element->getName());
                }
            }*/
        }

        return $form;
    }

    /**
     * @param AdminautEntityInterface $entity
     * @param Form $form
     * @param AdminautEntityInterface|null $parentEntity
     * @return AdminautEntityInterface
     */
    public function bind(AdminautEntityInterface $entity, Form $form, AdminautEntityInterface $parentEntity = null)
    {
        /* @var $element Element */
        foreach ($form->getElements() as $element) {
            $elementName = $element->getName();
            if ($elementName === 'reference_property') {
                if ($element->getValue() === 'parentId') {
                    $entity->{$element->getValue()} = $parentEntity->getId();
                } else {
                    $entity->{$element->getValue()} = $parentEntity;
                }
                continue;
            }

            if (method_exists($element, 'getInsertValue')) {
                $entity->{$elementName} = $element->getInsertValue();
            } else {
                $entity->{$elementName} = $element->getValue();
            }
        }
        return $entity;
    }

    /**
     * @param $moduleId
     * @return bool
     */
    public function hasModule($moduleId)
    {
        if (isset($this->modules[$moduleId]) && isset($this->modules[$moduleId]['type']) && 'module' === $this->modules[$moduleId]['type']) {
            return true;
        }
        return false;
    }

    /**
     * @param $moduleId
     * @return array|null
     */
    public function getModule($moduleId)
    {
        if ($this->hasModule($moduleId)) {
            return array_merge(['module_id' => $moduleId], $this->modules[$moduleId]);
        }
        return null;
    }

    /**
     * @param $moduleId
     * @return ModuleOptions
     * @throws \Exception
     */
    public function createModuleOptions($moduleId)
    {
        $module = $this->getModule($moduleId);

        if (null === $module || false === is_array($module)) {
            throw new \Exception('Failed to create module options.');
        }

        return new ModuleOptions($module);
    }
}
