<?php
namespace Adminaut\Form\Element;

use Traversable;
use Adminaut\Form\Element as MfccAdminFormElement;
use Zend\Form\Element;
use Zend\Form\Element\Checkbox as ZendCheckbox;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\InArray as InArrayValidator;

class Checkbox extends ZendCheckbox implements InputProviderInterface
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'single_checkbox'
    ];

    /**
     * @var string
     */
    protected $listedUncheckedValue = 'Yes';

    /**
     * @var string
     */
    protected $listedCheckedValue = 'No';

    /**
     * @var string
     */
    protected $checkboxLabel = '';

    /**
     * Accepted options for MultiCheckbox:
     * - use_hidden_element: do we render hidden element?
     * - unchecked_value: value for checkbox when unchecked
     * - checked_value: value for checkbox when checked
     *
     * @param  array|Traversable $options
     * @return Checkbox
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['listed_unchecked_value'])) {
            $this->setListedUncheckedValue($options['listed_unchecked_value']);
        }

        if (isset($options['listed_checked_value'])) {
            $this->setListedCheckedValue($options['listed_checked_value']);
        }

        if (isset($options['checkbox_label'])) {
            $this->setCheckboxLabel($options['checkbox_label']);
        }

        return $this;
    }

    /**
     * Set the value to use when checkbox is unchecked
     *
     * @param $uncheckedValue
     * @return Checkbox
     */
    public function setListedUncheckedValue($uncheckedValue)
    {
        $this->listedUncheckedValue = $uncheckedValue;
        return $this;
    }

    /**
     * Get the value to use when checkbox is unchecked
     *
     * @return string
     */
    public function getListedUncheckedValue()
    {
        return $this->listedUncheckedValue;
    }

    /**
     * Set the value to use when checkbox is checked
     *
     * @param $checkedValue
     * @return Checkbox
     */
    public function setListedCheckedValue($checkedValue)
    {
        $this->listedCheckedValue = $checkedValue;
        return $this;
    }

    /**
     * Get the value to use when checkbox is checked
     *
     * @return string
     */
    public function getListedCheckedValue()
    {
        return $this->listedCheckedValue;
    }

    /**
     * @return string
     */
    public function getListedValue()
    {
        if($this->isChecked()) {
            return '<span class="label label-success">'. $this->getListedCheckedValue() .'</span>';
        } else {
            return '<span class="label label-danger">'. $this->getListedUncheckedValue() .'</span>';
        }
    }

    /**
     * @return string
     */
    public function getCheckboxLabel()
    {
        return $this->checkboxLabel;
    }

    /**
     * @param string $checkboxLabel
     */
    public function setCheckboxLabel(string $checkboxLabel)
    {
        $this->checkboxLabel = $checkboxLabel;
    }
}
