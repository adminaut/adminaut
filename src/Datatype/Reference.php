<?php

namespace Adminaut\Datatype;

use Doctrine\Common\Annotations\AnnotationReader;
use Adminaut\Datatype\Reference\Proxy;
use RuntimeException;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Reflection\ClassReflection;
use Zend\Form\Annotation\Options;
use Zend\Form\Element;
use Zend\Form\ElementInterface;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Explode as ExplodeValidator;
use Zend\Validator\InArray as InArrayValidator;

class Reference extends Element implements InputProviderInterface
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
        'type' => 'datatypeReference',
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
    protected $visualization = "select";

    /**
     * @var bool
     */
    protected $subEntityReference = false;

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
            $repository = $this->getProxy()->getObjectManager()->getRepository($this->getProxy()->getTargetClass());
            return $repository->find($this->value);
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
        $record = $this->getValue(true);

        if ($record !== null) {
            return $record->{'get' . $this->getProxy()->getProperty()}();
        } else {
            return '';
        }
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

        $proxyValueOptions = $this->getProxy()->getValueOptions();

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
        if (in_array($visualization, ['select', 'radio'])) {
            $this->visualization = $visualization;
        } else {
            $this->visualization = 'select';
        }
    }

    /**
     * @return bool
     */
    public function isSubEntityReference()
    {
        return $this->subEntityReference;
    }

    /**
     * @param bool $isSubEntityReference
     */
    public function setSubEntityReference($isSubEntityReference)
    {
        $this->subEntityReference = $isSubEntityReference;
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
}