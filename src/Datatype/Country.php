<?php

namespace Adminaut\Datatype;

use PeterColes\Countries\Maker;
use Zend\I18n\Translator\TranslatorInterface;

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
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Country constructor.
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        if (empty($this->countries)) {
            $locale = $this->translator !== null ? $this->translator->getLocale() : 'en';
            $countryList = new Maker();
            $this->countries = $countryList->lookup($locale)->toArray();
        }

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
    public function setListName($listName)
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

        $this->datatypeSetOptions($options);
        return $this;
    }

    /**
     * @return array
     */
    public function getValueOptions()
    {
        if ($this->translator !== null) {
            $valueOptions = ['' => $this->translator->translate('Select country')];
        } else {
            $valueOptions = ['' => 'Select country'];
        }

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

    /**
     * @return TranslatorInterface|null
     */
    public function getTranslator(): ?TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * @param TranslatorInterface|null $translator
     */
    public function setTranslator(?TranslatorInterface $translator = null): void
    {
        $this->translator = $translator;
    }
}
