<?php

namespace Adminaut\Datatype;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use DoctrineModule\Form\Element\Proxy;
use RuntimeException;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Reflection\ClassReflection;
use Zend\Form\Annotation\Options;
use Zend\Form\Element;
use Zend\Form\ElementInterface;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Explode as ExplodeValidator;
use Zend\Validator\InArray as InArrayValidator;

/**
 * Class MultiReference
 * @package Adminaut\Datatype
 */
class MultiReference extends Element implements InputProviderInterface
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'datatypeMultiReference',
        'multiple' => true
    ];

    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @var \Zend\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @var bool
     */
    protected $disableInArrayValidator = false;

    /**
     * Create an empty option (option with label but no value). If set to null, no option is created
     *
     * @var bool
     */
    protected $emptyOption = null;

    /**
     * @var array
     */
    protected $valueOptions = [];

    /**
     * @var bool
     */
    protected $useHiddenElement = false;

    /**
     * @var string
     */
    protected $emptyValue = '';

    /**
     * @var string
     */
    protected $visualization = "checkbox";

    /**
     * @var string|null
     */
    protected $mask = null;

    /**
     * @return Proxy
     */
    public function getProxy()
    {
        if (null === $this->proxy) {
            $this->proxy = new Proxy();
        }
        return $this->proxy;
    }

    /**
     * @param  array|\Traversable $options
     * @return self
     */
    public function setOptions($options)
    {
        $this->getProxy()->setOptions($options);

        if (isset($this->options['empty_option'])) {
            $this->setEmptyOption($this->options['empty_option']);
        }

        if (isset($this->options['disable_inarray_validator'])) {
            $this->setDisableInArrayValidator($this->options['disable_inarray_validator']);
        }

        if (isset($options['use_hidden_element'])) {
            $this->setUseHiddenElement($options['use_hidden_element']);
        }

        if (isset($options['empty_value'])) {
            $this->setEmptyValue($options['empty_value']);
        }

        if (isset($options['unchecked_value'])) {
            $this->setEmptyValue($options['unchecked_value']);
        }

        if (isset($options['unselected_value'])) {
            $this->setEmptyValue($options['unselected_value']);
        }

        if (isset($options['visualization'])) {
            $this->setVisualization($options['visualization']);
        }

        if (!isset($options['property'])) {
            $this->getProxy()->setProperty($this->getPrimaryProperty());
        }

        if (isset($options['mask'])) {
            $this->setMask($options['mask']);
        }

        $this->datatypeSetOptions($options);
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setOption($key, $value)
    {
        $this->getProxy()->setOptions([$key => $value]);
        return parent::setOption($key, $value);
    }

    /**
     * Retrieve the element value
     *
     * @return mixed
     */
    public function getValue($returnObject = false)
    {
        if ($this->value === null) {
            return null;
        }

        if ($returnObject) {
            $proxy = $this->getProxy();
            $metadata = $proxy->getObjectManager()->getClassMetadata($proxy->getTargetClass());
            $criteria = new Criteria();

            foreach($metadata->getIdentifierFieldNames() as $field) {
                $criteria->orWhere($criteria->expr()->in($field, $this->value));
            }
            /** @var EntityRepository $repository */
            $repository = $proxy->getObjectManager()->getRepository($proxy->getTargetClass());
            $pr = $repository->matching($criteria);
            return $repository->matching($criteria);
        } else {
            return $this->value;
        }
    }

    /**
     * @return mixed
     */
    public function getInsertValue()
    {
        return $this->getValue(true);
    }

    public function getListedValue()
    {
        $result = [];
        $records = $this->getValue(true);
        foreach($records as $record) {
            if ($record !== null) {
                $result[] = $record->{'get' . $this->getProxy()->getProperty()}();
            } else {
                $result[] = '';
            }
        }

        return implode(', ', $result);
    }

    /**
     * {@inheritDoc}
     */
    public function setValue($value)
    {
        return parent::setValue($this->getProxy()->getValue($value));
    }

    /**
     * {@inheritDoc}
     */
    public function getValueOptions()
    {
        if (!empty($this->valueOptions)) {
            return $this->valueOptions;
        }

        if ($this->getMask()) {
            $this->loadValueOptionsWithMask();
            $proxyValueOptions = $this->valueOptions;
        } else {
            $proxyValueOptions = $this->getProxy()->getValueOptions();
        }

        if (!empty($proxyValueOptions)) {
            $this->setValueOptions($proxyValueOptions);
        }

        return $this->valueOptions;
    }

    /**
     * @param  array $options
     * @return self
     */
    public function setValueOptions(array $options)
    {
        $this->valueOptions = $options;

        // Update InArrayValidator validator haystack
        if (null !== $this->validator) {
            if ($this->validator instanceof InArrayValidator) {
                $validator = $this->validator;
            }
            if ($this->validator instanceof ExplodeValidator
                && $this->validator->getValidator() instanceof InArrayValidator
            ) {
                $validator = $this->validator->getValidator();
            }
            if (!empty($validator)) {
                $validator->setHaystack($this->getValueOptionsValues());
            }
        }

        return $this;
    }

    /**
     * Get only the values from the options attribute
     *
     * @return array
     */
    protected function getValueOptionsValues()
    {
        $values = [];
        $options = $this->getValueOptions();
        foreach ($options as $key => $optionSpec) {
            $value = (is_array($optionSpec)) ? $optionSpec['value'] : $key;
            $values[] = $value;
        }
        if ($this->useHiddenElement()) {
            $values[] = $this->getEmptyValue();
        }
        return $values;
    }

    /**
     * @param string $key
     * @return self
     */
    public function unsetValueOption($key)
    {
        if (isset($this->valueOptions[$key])) {
            unset($this->valueOptions[$key]);
        }

        return $this;
    }

    /**
     * Set a single element attribute
     *
     * @param  string $key
     * @param  mixed $value
     * @return self|ElementInterface
     */
    public function setAttribute($key, $value)
    {
        // Do not include the options in the list of attributes
        // TODO: Deprecate this
        if ($key === 'options') {
            $this->setValueOptions($value);
            return $this;
        }
        return parent::setAttribute($key, $value);
    }

    /**
     * Set the flag to allow for disabling the automatic addition of an InArray validator.
     *
     * @param bool $disableOption
     * @return self
     */
    public function setDisableInArrayValidator($disableOption)
    {
        $this->disableInArrayValidator = (bool)$disableOption;
        return $this;
    }

    /**
     * Get the disable in array validator flag.
     *
     * @return bool
     */
    public function disableInArrayValidator()
    {
        return $this->disableInArrayValidator;
    }

    /**
     * Set the string for an empty option (can be empty string). If set to null, no option will be added
     *
     * @param  string|null $emptyOption
     * @return self
     */
    public function setEmptyOption($emptyOption)
    {
        $this->emptyOption = $emptyOption;
        return $this;
    }

    /**
     * Return the string for the empty option (null if none)
     *
     * @return string|null
     */
    public function getEmptyOption()
    {
        return $this->emptyOption;
    }

    /**
     * Get validator
     *
     * @return \Zend\Validator\ValidatorInterface
     */
    protected function getValidator()
    {
        if (null === $this->validator && !$this->disableInArrayValidator()) {
            $validator = new InArrayValidator([
                'haystack' => $this->getValueOptionsValues(),
                'strict' => false
            ]);

            $validator = new ExplodeValidator([
                'validator'      => $validator,
                'valueDelimiter' => null, // skip explode if only one value
            ]);

            $this->validator = $validator;
        }
        return $this->validator;
    }

    /**
     * Do we render hidden element?
     *
     * @param  bool $useHiddenElement
     * @return self
     */
    public function setUseHiddenElement($useHiddenElement)
    {
        $this->useHiddenElement = (bool)$useHiddenElement;
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
     * @param string $emptyValue
     * @return self
     */
    public function setEmptyValue($emptyValue)
    {
        $this->emptyValue = (string)$emptyValue;
        return $this;
    }

    /**
     * Get the value when the select is not selected
     *
     * @return string
     */
    public function getEmptyValue()
    {
        return $this->emptyValue;
    }

    /**
     * @return null|string
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * @param null|string $mask
     */
    public function setMask($mask)
    {
        $this->mask = $mask;
    }

    /**
     * @return string
     */
    public function getVisualization()
    {
        return $this->visualization;
    }

    /**
     * @param string $visualization
     */
    public function setVisualization(string $visualization)
    {
        if (in_array($visualization, ['select', 'checkbox'])) {
            $this->visualization = $visualization;
        } else {
            $this->visualization = 'select';
        }
    }

    public function getPrimaryProperty()
    {
        $entityClass = $this->getProxy()->getTargetClass();
        $entity = new $entityClass();

        $annotationReader = new AnnotationReader();
        $reflectionObject = new \ReflectionObject($entity);
        foreach ($reflectionObject->getProperties() as $property) {
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

        if ($this->useHiddenElement()) {
            $unselectedValue = $this->getEmptyValue();

            $spec['allow_empty'] = true;
            $spec['continue_if_empty'] = true;
            $spec['filters'] = [[
                'name'    => 'Callback',
                'options' => [
                    'callback' => function ($value) use ($unselectedValue) {
                        if ($value === $unselectedValue) {
                            $value = [];
                        }
                        return $value;
                    }
                ]
            ]];
        }

        if ($validator = $this->getValidator()) {
            $spec['validators'] = [
                $validator,
            ];
        }

        return $spec;
    }

    /**
     * @return array
     */
    public function getObjectVars()
    {
        return get_object_vars($this);
    }


    /**
     * Load value options
     *
     * @throws RuntimeException
     * @return void
     */
    protected function loadValueOptionsWithMask()
    {
        if (!($om = $this->getProxy()->getObjectManager())) {
            throw new RuntimeException('No object manager was set');
        }

        if (!($targetClass = $this->getProxy()->getTargetClass())) {
            throw new RuntimeException('No target class was set');
        }

        $metadata = $om->getClassMetadata($targetClass);
        $identifier = $metadata->getIdentifierFieldNames();
        $objects = $this->getProxy()->getObjects();
        $options = [];
        $optionAttributes = [];
        $mask = $this->getMask();

        if ($this->getProxy()->getDisplayEmptyItem()) {
            $options[''] = $this->getProxy()->getEmptyItemLabel();
        }

        foreach ($objects as $key => $object) {
            preg_match_all("^%(.*?)%^", $mask, $matches);

            $label = $mask;
            foreach ($matches[1] as $property) {
                if ($this->getProxy()->getIsMethod() == false && !$metadata->hasField($property)) {
                    throw new RuntimeException(
                        sprintf(
                            'Property "%s" could not be found in object "%s"',
                            $property,
                            $targetClass
                        )
                    );
                }

                $getter = 'get' . ucfirst($property);

                if (!is_callable([$object, $getter])) {
                    throw new RuntimeException(
                        sprintf('Method "%s::%s" is not callable', $this->getProxy()->getTargetClass(), $getter)
                    );
                }

                $label = str_replace("%$property%", $object->{$getter}(), $label);
            }

            if (count($identifier) > 1) {
                $value = $key;
            } else {
                $value = current($metadata->getIdentifierValues($object));
            }

            foreach ($this->getProxy()->getOptionAttributes() as $optionKey => $optionValue) {
                if (is_string($optionValue)) {
                    $optionAttributes[$optionKey] = $optionValue;

                    continue;
                }

                if (is_callable($optionValue)) {
                    $callableValue = call_user_func($optionValue, $object);
                    $optionAttributes[$optionKey] = (string)$callableValue;

                    continue;
                }

                throw new RuntimeException(
                    sprintf(
                        'Parameter "option_attributes" expects an array of key => value where value is of type'
                        . '"string" or "callable". Value of type "%s" found.',
                        gettype($optionValue)
                    )
                );
            }

            // If no optgroup_identifier has been configured, apply default handling and continue
            if (is_null($this->getProxy()->getOptgroupIdentifier())) {
                $options[] = ['label' => $label, 'value' => $value, 'attributes' => $optionAttributes];

                continue;
            }

            // optgroup_identifier found, handle grouping
            $optgroupGetter = 'get' . ucfirst($this->getProxy()->getOptgroupIdentifier());

            if (!is_callable([$object, $optgroupGetter])) {
                throw new RuntimeException(
                    sprintf('Method "%s::%s" is not callable', $this->getProxy()->getTargetClass(), $optgroupGetter)
                );
            }

            $optgroup = $object->{$optgroupGetter}();

            // optgroup_identifier contains a valid group-name. Handle default grouping.
            if (false === is_null($optgroup) && trim($optgroup) !== '') {
                $options[$optgroup]['label'] = $optgroup;
                $options[$optgroup]['options'][] = [
                    'label' => $label,
                    'value' => $value,
                    'attributes' => $optionAttributes,
                ];

                continue;
            }

            $optgroupDefault = $this->getProxy()->getOptgroupDefault();

            // No optgroup_default has been provided. Line up without a group
            if (is_null($optgroupDefault)) {
                $options[] = ['label' => $label, 'value' => $value, 'attributes' => $optionAttributes];

                continue;
            }

            // Line up entry with optgroup_default
            $options[$optgroupDefault]['label'] = $optgroupDefault;
            $options[$optgroupDefault]['options'][] = [
                'label' => $label,
                'value' => $value,
                'attributes' => $optionAttributes,
            ];
        }

        $this->valueOptions = $options;
    }
}