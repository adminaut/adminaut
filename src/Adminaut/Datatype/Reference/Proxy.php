<?php
namespace Adminaut\Datatype\Reference;
use DoctrineModule\Form\Element\Exception\InvalidRepositoryResultException;
use ReflectionMethod;
use RuntimeException;

/**
 * Class Proxy
 * @package Adminaut\Datatype\Reference
 */
class Proxy extends \DoctrineModule\Form\Element\Proxy
{
    /**
     * @var string|null
     */
    protected $mask = null;

    /**
     * @var bool
     */
    protected $loaded = false;

    /**
     * @var array
     */
    protected $orderby = [
        'id' => 'ASC'
    ];

    /**
     * @param $objects
     */
    public function setObjects($objects) {
        $this->objects = $objects;
    }

    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['mask'])) {
            $this->setMask($options['mask']);
        }

        if (isset($options['orderby'])) {
            $this->setOrderby($options['orderby']);
        }
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
     * @return array
     */
    public function getOrderby()
    {
        return $this->orderby;
    }

    /**
     * @param array $orderby
     */
    public function setOrderby($orderby)
    {
        $this->orderby = $orderby;
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return $this->loaded;
    }

    /**
     * @param bool $loadEmpty
     */
    public function setLoaded($loaded)
    {
        $this->loaded = $loaded;
    }

    protected function loadObjects()
    {
        if(!$this->loaded) {
            if (! empty($this->objects)) {
                return;
            }

            $findMethod = (array) $this->getFindMethod();

            if (! $findMethod) {
                $findMethodName = 'findBy';
                $repository     = $this->objectManager->getRepository($this->targetClass);
                $objects        = $repository->findBy([
                    'deleted' => false
                ], $this->getOrderby());
            } else {
                if (! isset($findMethod['name'])) {
                    throw new RuntimeException('No method name was set');
                }
                $findMethodName   = $findMethod['name'];
                $findMethodParams = isset($findMethod['params']) ? array_change_key_case($findMethod['params']) : [];
                $repository       = $this->objectManager->getRepository($this->targetClass);

                if (! method_exists($repository, $findMethodName)) {
                    throw new RuntimeException(
                        sprintf(
                            'Method "%s" could not be found in repository "%s"',
                            $findMethodName,
                            get_class($repository)
                        )
                    );
                }

                $r    = new ReflectionMethod($repository, $findMethodName);
                $args = [];

                foreach ($r->getParameters() as $param) {
                    if (array_key_exists(strtolower($param->getName()), $findMethodParams)) {
                        $args[] = $findMethodParams[strtolower($param->getName())];
                    } elseif ($param->isDefaultValueAvailable()) {
                        $args[] = $param->getDefaultValue();
                    } elseif (! $param->isOptional()) {
                        throw new RuntimeException(
                            sprintf(
                                'Required parameter "%s" with no default value for method "%s" in repository "%s"'
                                . ' was not provided',
                                $param->getName(),
                                $findMethodName,
                                get_class($repository)
                            )
                        );
                    }
                }
                $objects = $r->invokeArgs($repository, $args);
            }

            $this->guardForArrayOrTraversable(
                $objects,
                sprintf('%s::%s() return value', get_class($repository), $findMethodName),
                'DoctrineModule\Form\Element\Exception\InvalidRepositoryResultException'
            );

            $this->objects = $objects;
        }
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
        } else {
            $this->loadValueOptions();
        }
        $this->loaded = true;

        return $this->valueOptions;
    }

    /**
     * Load value options
     *
     * @throws RuntimeException
     * @return void
     */
    protected function loadValueOptionsWithMask()
    {
        if (!($om = $this->getObjectManager())) {
            throw new RuntimeException('No object manager was set');
        }

        if (!($targetClass = $this->getTargetClass())) {
            throw new RuntimeException('No target class was set');
        }

        $metadata = $om->getClassMetadata($targetClass);
        $identifier = $metadata->getIdentifierFieldNames();
        $objects = $this->getObjects();
        $options = [];
        $optionAttributes = [];
        $mask = $this->getMask();

        if ($this->getDisplayEmptyItem()) {
            $options[''] = $this->getEmptyItemLabel();
        }

        foreach ($objects as $key => $object) {
            preg_match_all("^%(.*?)%^", $mask, $matches);

            $label = $mask;
            foreach ($matches[1] as $property) {
                if ($this->getIsMethod() == false && !$metadata->hasField($property)) {
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
                        sprintf('Method "%s::%s" is not callable', $this->getTargetClass(), $getter)
                    );
                }

                $label = str_replace("%$property%", $object->{$getter}(), $label);
            }

            if (count($identifier) > 1) {
                $value = $key;
            } else {
                $value = current($metadata->getIdentifierValues($object));
            }

            foreach ($this->getOptionAttributes() as $optionKey => $optionValue) {
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
            if (is_null($this->getOptgroupIdentifier())) {
                $options[] = ['label' => $label, 'value' => $value, 'attributes' => $optionAttributes];

                continue;
            }

            // optgroup_identifier found, handle grouping
            $optgroupGetter = 'get' . ucfirst($this->getOptgroupIdentifier());

            if (!is_callable([$object, $optgroupGetter])) {
                throw new RuntimeException(
                    sprintf('Method "%s::%s" is not callable', $this->getTargetClass(), $optgroupGetter)
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

            $optgroupDefault = $this->getOptgroupDefault();

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