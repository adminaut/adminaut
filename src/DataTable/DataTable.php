<?php
namespace Adminaut\DataTable;

use Adminaut\Entity\AdminautEntityInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Zend\Form\Annotation\Options;

class DataTable
{
    /**
     * @var array
     */
    protected $columns;

    /**
     * @var AdminautEntityInterface
     */
    protected $entity;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $primaryField;

    /**
     * @var array
     */
    protected $filterableFields;

    /**
     * @var array
     */
    protected $filterData;

    /**
     * @var array
     */
    protected $searchableFields;

    public function __construct($entityOrColumns, $data = []) {
        if(gettype($entityOrColumns) == "array") {
            $this->setColumns($entityOrColumns);
        } else {
            $this->setEntity($entityOrColumns);
            $this->getColumnsByEntity();
        }
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    /**
     * @param string $column
     */
    public function addColumn($name, $label) {
        $this->columns[$name] = $label;
    }

    /**
     * @return AdminautEntityInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param AdminautEntityInterface $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getPrimaryField()
    {
        return $this->primaryField;
    }

    /**
     * @param string $primaryField
     */
    public function setPrimaryField($primaryField)
    {
        $this->primaryField = $primaryField;
    }

    /**
     * @return array
     */
    public function getFilterData()
    {
        return $this->filterData;
    }

    /**
     * @param array $filterData
     */
    public function setFilterData($filterData)
    {
        $this->filterData = $filterData;
    }

    public function getColumnsByEntity() {
        $entity = $this->getEntity();

        $annotationReader = new AnnotationReader();
        $reflectionObject = new \ReflectionObject($entity);
        foreach ($reflectionObject->getProperties() as $property) {
            $annotations = $annotationReader->getPropertyAnnotations($property);

            foreach ($annotations as $annotation) {
                if (!$annotation instanceof Options) {
                    continue;
                }

                $options = $annotation->getOptions();
                if((isset($options['primary']) && $options['primary']) || (isset($options['listed']) && $options['listed'])) {
                    $this->addColumn($property, $options['label']);
                }

                if(isset($options['filterable']) && $options['filterable']) {
                    $this->addFilterableField($property);
                }

                if(isset($options['searchable']) && $options['searchable']) {
                    $this->addSearchableField($property);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getFilterableFields()
    {
        return $this->filterableFields;
    }

    /**
     * @param array $filterableFields
     */
    public function setFilterableFields($filterableFields)
    {
        $this->filterableFields = $filterableFields;
    }

    /**
     * @param string $fiterableField
     */
    public function addFilterableField($fiterableField) {
        $this->filterableFields[] = $fiterableField;
    }

    /**
     * @return array
     */
    public function getSearchableFields()
    {
        return $this->searchableFields;
    }

    /**
     * @param array $searchableFields
     */
    public function setSearchableFields($searchableFields)
    {
        $this->searchableFields = $searchableFields;
    }

    /**
     * @param string $searchableField
     */
    public function addSearchableField($searchableField) {
        $this->searchableFields[] = $searchableField;
    }
}