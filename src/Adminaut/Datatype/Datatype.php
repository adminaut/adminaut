<?php

namespace Adminaut\Datatype;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Trait Datatype
 * @package Adminaut\Datatype
 */
trait Datatype
{
    /**
     * @var Form
     */
    protected $form = null;

    /**
     * @var bool
     */
    protected $primary = false;

    /**
     * @var bool
     */
    protected $listed = false;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var bool
     */
    protected $filterable = false;

    /**
     * @var bool
     */
    protected $searchable = false;

    /**
     * @var string|null
     */
    protected $defaultSort = null;

    /**
     * @return bool
     */
    public function isListed()
    {
        return $this->listed;
    }

    /**
     * @param bool $listed
     */
    public function setListed(bool $listed)
    {
        $this->listed = $listed;
    }

    /**
     * @return bool
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * @param bool $primary
     */
    public function setPrimary(bool $primary)
    {
        $this->primary = $primary;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired($required)
    {
        // TODO: REQUIRED IN OPTIONS
        //$this->setAttribute('required', 'required');
        $this->required = $required;
    }

    /**
     * @return bool
     */
    public function isFilterable()
    {
        return $this->filterable;
    }

    /**
     * @param bool $filterable
     */
    public function setFilterable($filterable)
    {
        $this->filterable = $filterable;
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * @param bool $searchable
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;
    }

    /**
     * @return null|string
     */
    public function getDefaultSort()
    {
        return $this->defaultSort;
    }

    /**
     * @param null|string $defaultSort
     */
    public function setDefaultSort($defaultSort)
    {
        $this->defaultSort = $defaultSort;
    }

    /**
     * @param  array $options
     * @return Element
     */
    public function setOptions($options)
    {
        if (isset($options['listed'])) {
            $this->setListed($options['listed']);
        } else {
            $options['listed'] = $this->isListed();
        }

        if (isset($options['primary'])) {
            $this->setPrimary($options['primary']);
            if ($this->isPrimary()) {
                $this->setListed(true);
                $options['listed'] = true;
            }
        } else {
            $options['primary'] = $this->isPrimary();
        }

        parent::setOptions($options);
        return $this;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    public function getListedValue()
    {
        return $this->getValue();
    }

    /**
     * @return mixed
     */
    public function getInsertValue()
    {
        return $this->getValue();
    }

    /**
     * @return mixed
     */
    public function getEditValue()
    {
        return $this->getValue();
    }
}