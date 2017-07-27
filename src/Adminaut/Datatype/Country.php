<?php

namespace Adminaut\Datatype;

use League\ISO3166\ISO3166;

/**
 * Class Country
 * @package Adminaut\Datatype
 */
class Country extends Select
{
    /**
     * @var array
     */
    protected $countries = [];

    /**
     * @var null|array
     */
    protected $availableCountries = null;

    /**
     * @var bool
     */
    protected $listName = true;

    /**
     * Country constructor.
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $iso3166 = new ISO3166();

        foreach ($iso3166->all() as $country) {
            $this->countries[$country['alpha2']] = $country['name'];
        }
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param $iso
     * @return string|null
     */
    public function getCountry($iso)
    {
        if (isset($this->getCountries()[$iso])) {
            return $this->getCountries()[$iso];
        }

        return null;
    }

    /**
     * @param array $countries
     */
    public function setCountries(array $countries)
    {
        $this->countries = $countries;
    }

    /**
     * @return array|null
     */
    public function getAvailableCountries()
    {
        return $this->availableCountries;
    }

    /**
     * @param array|null $availableCountries
     */
    public function setAvailableCountries($availableCountries)
    {
        $this->availableCountries = $availableCountries;
    }

    /**
     * @return bool
     */
    public function isListName()
    {
        return $this->listName;
    }

    /**
     * @param bool $listName
     */
    public function setListName(bool $listName)
    {
        $this->listName = $listName;
    }

    /**
     * @param array|\Traversable $options
     * @return Select
     */
    public function setOptions($options)
    {
        if (isset($options['availableCountries'])) {
            if (is_array($options['availableCountries'])) {
                $this->setAvailableCountries($options['availableCountries']);
            } else {
                $this->setAvailableCountries([$options['availableCountries']]);
            }
        }

        if (isset($options['listName'])) {
            $this->setListName($options['listName']);
        }

        return $this->datatypeSetOptions($options);
    }

    /**
     * @return array
     */
    public function getValueOptions()
    {
        $valueOptions = ['' => 'Select country...'];
        if (is_array($this->getAvailableCountries())) {
            foreach ($this->getAvailableCountries() as $country) {
                if (!isset($this->getCountries()[$country])) {
                    continue;
                }

                $valueOptions[$country] = $this->getCountries()[$country];
            }

            return $valueOptions;
        } else {
            return array_merge($valueOptions, $this->getCountries());
        }
    }

    /**
     * @return null|string
     */
    public function getListedValue()
    {
        if ($this->isListName()) {
            return $this->getCountry($this->getValue());
        } else {
            return $this->getValue();
        }
    }

    public function setValueOptions(array $options)
    {
        return $this;
    }
}
