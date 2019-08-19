<?php

namespace Adminaut\Manager;

use Adminaut\Datatype\Datatype;
use Adminaut\Datatype\DatatypeInterface;
use Adminaut\Datatype\MultiReference;
use Adminaut\Datatype\Reference;
use Adminaut\Exception\DuplicateValueForUniqueException;
use Adminaut\Form\Annotation\AnnotationBuilder;
use Adminaut\Form\Element\CyclicSheet;
use Adminaut\Service\AccessControlService;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
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
use Zend\Form\ElementInterface;
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

    /**
     * @var array
     */
    private $processedColumns = [];

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

        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            var_dump($e); die();
        }

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

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            preg_match('/Duplicate entry \'([^\']*)\' for key \'([^\']*)\'/', $e->getMessage(), $matches);

            if (isset($matches[1])) {
                $invalidValue = $matches[1];

                if (isset($matches[2])) {
                    $uniqueKey = $matches[2];

                    $classMetadata = $this->entityManager->getClassMetadata(get_class($entity));
                    $_tableIndexes = $this->entityManager->getConnection()->getSchemaManager()->listTableIndexes($classMetadata->getTableName());

                    if (array_key_exists(strtolower($uniqueKey), $_tableIndexes)) {
                        $column = array_shift($_tableIndexes[strtolower($uniqueKey)]->getColumns());
                        $field = $classMetadata->getFieldForColumn($column);

                        throw new DuplicateValueForUniqueException($invalidValue, $column, $field, 0, $e);
                    }
                }

                throw new DuplicateValueForUniqueException($invalidValue, null ,null, 0, $e);
            }

            throw $e;
        }

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
    public function getDatatable(string $entityName, array $criteria = [], array $searchableElements = [], string $searchString = "", array $columnSearch = [], array $orders = [])
    {
        $criteria = array_merge(['deleted' => false], $criteria);

        $repository = $this->getRepository($entityName);

        $joined = [];
        $qb = $repository->createQueryBuilder('e');

        $this->applyCriteriaToQueryBuilder($qb, $joined, $criteria);
        $this->applySearchToQueryBuilder($qb, $joined, $searchableElements, $searchString);
        $this->applyColumnSearchToQueryBuilder($qb, $joined, $columnSearch);

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

    public function getDatatableFilters(string $entityName, array $filterableColumns, array $criteria = [], array $searchableElements = [], string $searchString = "")
    {
        $criteria = array_merge(['deleted' => false], $criteria);
        $repository = $this->getRepository($entityName);
        $qb = $repository->createQueryBuilder('e');
        $joined = [];

        $this->applyCriteriaToQueryBuilder($qb, $joined, $criteria);
        $this->applySearchToQueryBuilder($qb, $joined, $searchableElements, $searchString);

        $filters = [];
        /**
         * @var string $filterableColumn
         * @var Datatype|ElementInterface $filterableColumnDatatype
         */
        foreach ($filterableColumns as $filterableColumn => $filterableColumnDatatype) {
            $sQb = clone $qb;

            $s = sprintf('e.%s', $filterableColumn);
            $sQb->select($s)->distinct();

            if ( $filterableColumnDatatype->getOption('target_class') ) {
                $sQb->select(sprintf('%s.id', str_replace('.', '_', $s)))->distinct();

                if ( !in_array($filterableColumn, $joined) ) {
                    $sQb->leftJoin($s, str_replace('.', '_', $s));
                }

                $targetRepository = $this->entityManager->getRepository($filterableColumnDatatype->getOption('target_class'));
                $targetQb = $targetRepository->createQueryBuilder($filterableColumn);
                $sQb = $targetQb->select($filterableColumn)
                    ->where($sQb->expr()->in(sprintf('%s.id', $filterableColumn), $sQb->getDQL()))
                    ->setParameters($sQb->getParameters());
            } else {
                $sQb->andWhere('LENGTH('. $s .') > 0')
                    ->andWhere($s . ' IS NOT NULL')
                    ->orderBy($s, 'asc');
            }

            $q = $sQb->getDQL();
            $result = $sQb->getQuery()->getResult();
            $filters[$filterableColumn] = array_map(function($data) use ($filterableColumnDatatype) {
                $filterableColumnDatatype->setValue(is_array($data) && sizeof($data) == 1 ? current($data) : $data);

                if ( method_exists( $filterableColumnDatatype, 'getFilterValue' ) ) {
                    return $filterableColumnDatatype->getFilterValue();
                } elseif ( method_exists( $filterableColumnDatatype, 'getListedValue' ) ) {
                    return $filterableColumnDatatype->getListedValue();
                } else {
                    return $filterableColumnDatatype->getValue();
                }
            }, $result);
        }

        return $filters;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $criteria
     */
    private function applyCriteriaToQueryBuilder(QueryBuilder &$qb, array &$joined, $criteria = [])
    {
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

                $qb->andWhere("e_$joinAlias.$x = :ce_$joinAlias_$x");
                $qb->setParameter("ce_$joinAlias_$x", $criterionValue);
            } else {
                $qb->andWhere("e.$criterionField = :ce_$criterionField");
                $qb->setParameter("ce_$criterionField", $criterionValue);
            }
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param array $criteria
     */
    private function applyColumnSearchToQueryBuilder(QueryBuilder &$qb, array &$joined, $columnSearch = [])
    {
        foreach ($columnSearch as $column => $value) {
            $qb->andWhere("e.$column = :cse_$column");
            $qb->setParameter("cse_$column", $value);
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param array $searchableElements
     * @param string $searchString
     */
    private function applySearchToQueryBuilder(QueryBuilder &$qb, array &$joined, array $searchableElements = [], string $searchString)
    {
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
    public function getListedColumns($moduleId, $form = null)
    {
        if ( ! array_key_exists('listed', $this->processedColumns) ) {
            $this->processColumns($moduleId, $form);

            if ( ! array_key_exists('listed', $this->processedColumns) ) {
                new \Exception("Can't process listed columns.");
            }
        }

        return $this->processedColumns['listed'];
    }

    /**
     * @param $moduleId
     * @param Form|null $form
     * @return array
     * @throws \Exception
     * @deprecated Use getListedColumns($moduleId, $form = null)
     */
    public function getListedElements($moduleId, $form = null)
    {
        return $this->getListedColumns($moduleId, $form);
    }

    /**
     * @param $moduleId
     * @param Form|null $form
     * @return array
     * @throws \Exception
     */
    public function getDatatableColumns($moduleId, $form = null)
    {
        if ( ! array_key_exists('datatable', $this->processedColumns) ) {
            $this->processColumns($moduleId, $form);

            if ( ! array_key_exists('datatable', $this->processedColumns) ) {
                new \Exception("Can't process datatable columns.");
            }
        }

        return $this->processedColumns['datatable'];
    }

    /**
     * @param string $moduleId
     * @param Form|null $form
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getSearchableColumns($moduleId, $form = null)
    {
        if ( ! array_key_exists('searchable', $this->processedColumns) ) {
            $this->processColumns($moduleId, $form);

            if ( ! array_key_exists('searchable', $this->processedColumns) ) {
                new \Exception("Can't process searchable columns.");
            }
        }

        return $this->processedColumns['searchable'];
    }

    /**
     * @param string $moduleId
     * @param Form|null $form
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @deprecated Use getSearchableColumns($moduleId, $form = null)
     */
    public function getSearchableElements($moduleId, $form = null)
    {
        return $this->getSearchableColumns($moduleId, $form);
    }

    /**
     * @param string $moduleId
     * @param Form|null $form
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getFilterableColumns($moduleId, $form = null)
    {
        if ( ! array_key_exists('filterable', $this->processedColumns) ) {
            $this->processColumns($moduleId, $form);

            if ( ! array_key_exists('filterable', $this->processedColumns) ) {
                new \Exception("Can't process filterable columns.");
            }
        }

        return $this->processedColumns['filterable'];
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
        $dtColumnNames = array_keys($datatableColumns);

        foreach ($dtOrders as $dtOrder) {
            $column = (int) $dtOrder['column'];
            $dir = $dtOrder['dir'];
            $elementKey = $datatableColumns[$dtColumnNames[$column]]['data'];

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

    private function processColumns($moduleId, $form = null)
    {
        if(!$form instanceof Form) {
            $form = $this->moduleManager->createForm($this->createModuleOptions($moduleId));
        }

        $listedElements = [];
        $searchableElements = [];
        $filterableColumns = [];
        $datatableColumns = [
            'id' => [
                'data' => 'id',
                'searchable' => false,
                'filterable' => false,
            ],
        ];

        /* @var $element \Zend\Form\Element */
        foreach ($form->getElements() as $key => $element) {
            if ($element->getOption('listed')
                && $this->accessControlService->isAllowed($moduleId, AccessControlService::READ, $key)
                || (method_exists($element, 'isPrimary')
                    && $element->isPrimary()
                    || $element->getOption('primary'))
            ) {
                $listedElements[$key] = $element;

                $datatableColumns[$key] = [
                    'data' => $key,
                    'searchable' => $element->getOption('searchable') ?: false,
                    'filterable' => $element->getOption('filterable') ?: false,
                    'order' => $element->getOption('sort') ?: false,
                ];
            }

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

            if ($element->getOption('filterable')
                && ($this->accessControlService->isAllowed($moduleId, AccessControlService::READ, $key)
                || (method_exists($element, 'isPrimary') && $element->isPrimary() || $element->getOption('primary')))
            ) {
                $filterableColumns[$key] = $element;
            }
        }

        $datatableColumns['actions'] = [
            'data' => 'actions',
            'searchable' => false,
            'filterable' => false,
            'orderable' => false,
        ];

        $this->processedColumns = [
            'listed' => $listedElements,
            'searchable' => $searchableElements,
            'datatable' => $datatableColumns,
            'filterable' => $filterableColumns
        ];
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
