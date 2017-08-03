<?php

namespace Adminaut\Datatype;

use Gettext\Languages\Language as Languages;

/**
 * Class Language
 * @package Adminaut\Datatype
 */
class Language extends Select
{
    /**
     * @var array
     */
    protected $languages = [];

    /**
     * @var null|array
     */
    protected $availableLanguages = null;

    /**
     * @var bool
     */
    protected $listName = true;

    /**
     * Language constructor.
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $allLanguages = Languages::getAll();

        foreach ($allLanguages as $language) {
            $this->languages[$language->id] = $language->name;
        }
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param $id
     * @return string|null
     */
    public function getLanguage($id)
    {
        if (isset($this->getLanguages()[$id])) {
            return $this->getLanguages()[$id];
        }

        return null;
    }

    /**
     * @param array $languages
     */
    public function setLanguages(array $languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return array|null
     */
    public function getAvailableLanguages()
    {
        return $this->availableLanguages;
    }

    /**
     * @param array|null $availableLanguages
     */
    public function setAvailableLanguages($availableLanguages)
    {
        $this->availableLanguages = $availableLanguages;
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
        if (isset($options['availableLanguages'])) {
            if (is_array($options['availableLanguages'])) {
                $this->setAvailableLanguages($options['availableLanguages']);
            } else {
                $this->setAvailableLanguages([$options['availableLanguages']]);
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
        $valueOptions = ['' => 'Select language'];
        if (is_array($this->getAvailableLanguages())) {
            foreach ($this->getAvailableLanguages() as $language) {
                if (!isset($this->getLanguages()[$language])) {
                    continue;
                }

                $valueOptions[$language] = $this->getLanguages()[$language];
            }

            return $valueOptions;
        } else {
            return array_merge($valueOptions, $this->getLanguages());
        }
    }

    /**
     * @return null|string
     */
    public function getListedValue()
    {
        if ($this->isListName()) {
            return $this->getLanguage($this->getValue());
        } else {
            return $this->getValue();
        }
    }

    public function setValueOptions(array $options)
    {
        return $this;
    }
}
