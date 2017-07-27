<?php
namespace Adminaut\Datatype;

/**
 * Class Checkbox
 * @package Adminaut\Datatype
 */
class Checkbox extends \Zend\Form\Element\Checkbox
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'datatypeCheckbox'
    ];

    /**
     * @var string
     */
    protected $listedCheckedValue = 'Yes';

    /**
     * @var string
     */
    protected $listedUncheckedValue = 'No';

    /**
     * @var string
     */
    protected $checkboxLabel = '';

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        parent::setOptions($options);
        $this->datatypeSetOptions($options);

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
     * @param $uncheckedValue
     * @return $this
     */
    public function setListedUncheckedValue($uncheckedValue)
    {
        $this->listedUncheckedValue = $uncheckedValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getListedUncheckedValue()
    {
        return $this->listedUncheckedValue;
    }

    /**
     * @param $checkedValue
     * @return $this
     */
    public function setListedCheckedValue($checkedValue)
    {
        $this->listedCheckedValue = $checkedValue;
        return $this;
    }

    /**
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
