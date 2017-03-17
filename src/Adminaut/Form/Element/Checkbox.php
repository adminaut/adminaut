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
        'type' => 'checkbox'
    ];

    /**
     * @var \Zend\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @var bool
     */
    protected $useHiddenElement = true;

    /**
     * @var string
     */
    protected $uncheckedValue = '0';

    /**
     * @var string
     */
    protected $checkedValue = '1';

    /**
     * @var string
     */
    protected $listedUncheckedValue = 'Yes';

    /**
     * @var string
     */
    protected $listedCheckedValue = 'No';

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

        if (isset($options['use_hidden_element'])) {
            $this->setUseHiddenElement($options['use_hidden_element']);
        }

        if (isset($options['unchecked_value'])) {
            $this->setUncheckedValue($options['unchecked_value']);
        }

        if (isset($options['checked_value'])) {
            $this->setCheckedValue($options['checked_value']);
        }

        if (isset($options['listed_unchecked_value'])) {
            $this->setListedUncheckedValue($options['listed_unchecked_value']);
        }

        if (isset($options['listed_checked_value'])) {
            $this->setListedCheckedValue($options['listed_checked_value']);
        }

        return $this;
    }

    /**
     * Do we render hidden element?
     *
     * @param  bool $useHiddenElement
     * @return Checkbox
     */
    public function setUseHiddenElement($useHiddenElement)
    {
        $this->useHiddenElement = (bool) $useHiddenElement;
        return $this;
    }

    /**
     * Do we render hidden element?
     *
     * @return bool
     */
    public function useHiddenElement()
    {
        return $this->useHiddenElement;
    }

    /**
     * Set the value to use when checkbox is unchecked
     *
     * @param $uncheckedValue
     * @return Checkbox
     */
    public function setUncheckedValue($uncheckedValue)
    {
        $this->uncheckedValue = $uncheckedValue;
        return $this;
    }

    /**
     * Get the value to use when checkbox is unchecked
     *
     * @return string
     */
    public function getUncheckedValue()
    {
        return $this->uncheckedValue;
    }

    /**
     * Set the value to use when checkbox is checked
     *
     * @param $checkedValue
     * @return Checkbox
     */
    public function setCheckedValue($checkedValue)
    {
        $this->checkedValue = $checkedValue;
        return $this;
    }

    /**
     * Get the value to use when checkbox is checked
     *
     * @return string
     */
    public function getCheckedValue()
    {
        return $this->checkedValue;
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
     * Get validator
     *
     * @return \Zend\Validator\ValidatorInterface
     */
    protected function getValidator()
    {
        if (null === $this->validator) {
            $this->validator = new InArrayValidator([
                'haystack' => [$this->checkedValue, $this->uncheckedValue],
                'strict'   => false
            ]);
        }
        return $this->validator;
    }

    /**
     * Provide default input rules for this element
     *
     * Attaches the captcha as a validator.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        $spec = [
            'name' => $this->getName(),
            'required' => true,
        ];

        if ($validator = $this->getValidator()) {
            $spec['validators'] = [
                $validator,
            ];
        }

        return $spec;
    }

    /**
     * Checks if this checkbox is checked.
     *
     * @return bool
     */
    public function isChecked()
    {
        return $this->value === $this->getCheckedValue();
    }

    /**
     * Checks or unchecks the checkbox.
     *
     * @param bool $value The flag to set.
     * @return Checkbox
     */
    public function setChecked($value)
    {
        $this->value = $value ? $this->getCheckedValue() : $this->getUncheckedValue();
        return $this;
    }

    /**
     * Checks or unchecks the checkbox.
     *
     * @param mixed $value A boolean flag or string that is checked against the "checked value".
     * @return Element
     */
    public function setValue($value)
    {
        // Cast to strings because POST data comes in string form
        $checked = (string) $value === (string) $this->getCheckedValue();
        $this->value = $checked ? $this->getCheckedValue() : $this->getUncheckedValue();
        return $this;
    }
}
