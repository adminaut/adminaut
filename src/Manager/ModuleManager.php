<?php

namespace Adminaut\Manager;

use Adminaut\Datatype\Datatype;
use Adminaut\Datatype\MultiReference;
use Adminaut\Datatype\Reference;
use Adminaut\Form\Annotation\AnnotationBuilder;
use Adminaut\Form\Element\CyclicSheet;
use Adminaut\Service\AccessControlService;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\NoResultException;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use DoctrineModule\Form\Element\ObjectRadio;
use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Entity\UserEntityInterface;
use Adminaut\Options\ModuleOptions;
use Adminaut\Form\Form;
use Zend\Form\Annotation\Options;
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

    /**
     * @var AccessControlService
     */
    private $accessControlService;

    //-------------------------------------------------------------------------

    /**
     * ModuleManager constructor.
     * @param EntityManager $entityManager
     * @param array $modules
     */
    public function __construct(
        EntityManager $entityManager,
        AccessControlService $accessControlService,
        array $modules = []
    ) {
        parent::__construct($entityManager);

        $this->accessControlService = $accessControlService;
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
     * @param string $entityName
     * @param array $criteria
     * @param array $searchableElements
     * @param string $searchString
     * @param array $orders
     */
    public function getDatatable(string $entityName, array $criteria = [], array $searchableElements = [], string $searchString = "", array $orders = [])
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
                        $qb->leftJoin('e.' . $join, 'e_' . $joinAlias);
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

        if(!empty($searchString)) {
            $searchQB = $qb->expr()->orX();
            foreach ($searchableElements as $criterionField) {
                if (strpos($criterionField, '.')) {
                    $join = "";
                    $joinAlias = "";
                    foreach ($a = explode('.', $criterionField) as $x) {
                        if ($x === end($a)) {
                            break;
                        }

                        $join .= (!empty($join) ? '.' : '') . $x;
                        $joinAlias = str_replace('.', '_', $join);

                        if (!in_array($join, $joined)) {
                            $qb->leftJoin('e.' . $join, 'e_' . $joinAlias);
                            $joined[] = $join;
                        }
                    }

                    $searchQB->add($qb->expr()->like("e_$joinAlias.$x", ":search"));
                } else {
                    $searchQB->add($qb->expr()->like("e.$criterionField", ":search"));
                }
            }

            $qb->andWhere($searchQB);
            $qb->setParameter("search", sprintf("%%%s%%", $searchString));
        }

        // Order
        foreach ($orders as $order) {
            $orderField = $order['order_by'];

            if(strpos($orderField, '.')) {
                $join = "";
                $joinAlias = "";
                foreach ($a = explode('.', $orderField) as $x) {
                    if($x === end($a)) { break; }

                    $join .= (!empty($join) ? '.' : '') . $x;
                    $joinAlias = str_replace('.', '_', $join);

                    if(!in_array($join, $joined)) {
                        $qb->leftJoin('e.' . $join, 'e_' . $joinAlias);
                        $joined[] = $join;
                    }
                }

                $qb->addOrderBy("e_$joinAlias.$x", $order["order_type"]);
            } else {
                $qb->addOrderBy("e.$orderField", $order["order_type"]);
            }
        }

        return new ORMPaginator($qb->getQuery());
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

    /**
     * @param $moduleId
     * @param Form|null $form
     * @return array
     * @throws \Exception
     */
    public function getListedElements($moduleId, $form = null)
    {
        if(!$form instanceof Form) {
            $form = $this->moduleManager->createForm($this->createModuleOptions($moduleId));
        }

        $listedElements = [];

        /* @var $element \Zend\Form\Element */
        foreach ($form->getElements() as $key => $element) {
            if ($element->getOption('listed')
                && $this->accessControlService->isAllowed($moduleId, AccessControlService::READ, $key)
                || (method_exists($element, 'isPrimary')
                    && $element->isPrimary()
                    || $element->getOption('primary'))
            ) {
                $listedElements[$key] = $element;
            }
        }

        return $listedElements;
    }

    /**
     * @param $moduleId
     * @param Form|null $form
     * @return array
     * @throws \Exception
     */
    public function getDatatableColumns($moduleId, $form = null)
    {
        $datatableColumns = [];
        $listedElements = $this->getListedElements($moduleId, $form);

        $datatableColumns[] = [
            'data' => 'id',
            'searchable' => false,
        ];

        /**
         * @var string $key
         * @var Datatype|Element $listedElement
         */
        foreach ($listedElements as $key => $listedElement) {
            $datatableColumn = [
                'data' => $key,
                'searchable' => $listedElement->getOption('searchable') ?: false,
                'filtrable' => $listedElement->getOption('filtrable') ?: false,

            ];

            if($listedElement->getOption('sort')) {
                $datatableColumn['order'] = $listedElement->getOption('sort');
            }

            $datatableColumns[] = $datatableColumn;
        }

        $datatableColumns[] = [
            'data' => 'actions',
            'searchable' => false,
            'orderable' => false,
        ];

        return $datatableColumns;
    }

    /**
     * @param string $moduleId
     * @param Form|null $form
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getSearchableElements($moduleId, $form = null)
    {
        if(!$form instanceof Form) {
            $form = $this->moduleManager->createForm($this->createModuleOptions($moduleId));
        }

        $searchableElements = [];

        /* @var $element \Zend\Form\Element */
        foreach ($form->getElements() as $key => $element) {
            if ($element->getOption('searchable')
                && $this->accessControlService->isAllowed($moduleId, AccessControlService::READ, $key)
                || (method_exists($element, 'isPrimary')
                    && $element->isPrimary()
                    || $element->getOption('primary'))
            ) {
                if ( $element->getOption('target_class') ) {
                    if ( $element->getOption('mask') ) {
                        preg_match_all("^%(.*?)%^", $element->getOption('mask'), $matches);
                        foreach ($matches[1] as $property) {
                            $searchableElements[] = sprintf('%s.%s', $key, $property);
                        }
                    } elseif ( $element->getOption('property') ) {
                        $searchableElements[] = sprintf('%s.%s', $key, $element->getOption('property'));
                    } else {
                        $searchableElements[] = sprintf('%s.%s', $key, $this->getEntityPrimaryProperty($element->getOption('target_class')));
                    }
                } else {
                    $searchableElements[] = $key;
                }
            }
        }

        return $searchableElements;
    }

    /**
     * @param array $dtOrders
     * @param $moduleId
     * @param null $form
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getOrderedElements(array $dtOrders, $moduleId, $form = null)
    {
        if(!$form instanceof Form) {
            $form = $this->moduleManager->createForm($this->createModuleOptions($moduleId));
        }

        $orders = [];
        $datatableColumns = $this->getDatatableColumns($moduleId, $form);

        foreach ($dtOrders as $dtOrder) {
            $column = $dtOrder['column'];
            $dir = $dtOrder['dir'];
            $elementKey = $datatableColumns[$column]['data'];

            if ($form->has($elementKey)) {
                $element = $form->get($elementKey);

                if ( $element->getOption('target_class') ) {
                    if ( $element->getOption('mask') ) {
                        preg_match_all("^%(.*?)%^", $element->getOption('mask'), $matches);
                        foreach ($matches[1] as $property) {
                            $orders[] = ['order_by' => sprintf('%s.%s', $elementKey, $property), 'order_type' => $dir];
                        }
                    } elseif ( $element->getOption('property') ) {
                        $orders[] = ['order_by' => sprintf('%s.%s', $elementKey, $element->getOption('property')), 'order_type' => $dir];
                    } else {
                        $orders[] = ['order_by' => sprintf('%s.%s', $elementKey, $this->getEntityPrimaryProperty($element->getOption('target_class'))), 'order_type' => $dir];
                    }
                } else {
                    $orders[] = ['order_by' => $elementKey, 'order_type' => $dir];
                }
            }
        }

        return $orders;
    }

    /**
     * @param string $entityClass
     * @return null|string
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    protected function getEntityPrimaryProperty(string $entityClass)
    {
        $annotationReader = new AnnotationReader();

        foreach ($this->getEntityProperties($entityClass) as $property) {
            $annotations = $annotationReader->getPropertyAnnotations($property);

            foreach ($annotations as $annotation) {
                if (!$annotation instanceof Options) {
                    continue;
                }

                $options = $annotation->getOptions();
                if (isset($options['primary']) && $options['primary'] === true) {
                    return $property->getName();
                }
            }
        }

        return null;
    }

    /**
     * @param string $entityClass
     * @return \ReflectionProperty[]
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    protected function getEntityProperties(string $entityClass)
    {
        $entityClass = $entityClass;
        $entity = new $entityClass();

        $annotationReader = new AnnotationReader();
        $reflectionObject = new \ReflectionObject($entity);

        return $reflectionObject->getProperties();
    }
}
