<?php
namespace Adminaut\Form;


use Adminaut\Form\Form;
use Adminaut\Datatype\DatatypeManager;
use Traversable;
use Zend\Form\ElementInterface as ZFElementInterface;
use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class Factory
 * @package Adminaut\Form
 */
class Factory extends \Zend\Form\Factory
{
    /**
     * @var DatatypeManager
     */
    private $datatypeManager;

    /**
     * Set the form element manager
     *
     * @param  DatatypeManager $datatypeManager
     * @return Factory
     */
    public function setDatatypeManager(DatatypeManager $datatypeManager)
    {
        $this->datatypeManager = $datatypeManager;
        return $this;
    }

    /**
     * Get form element manager
     *
     * @return DatatypeManager
     */
    public function getDatatypeManager()
    {
        if ($this->datatypeManager === null) {
            $this->setDatatypeManager(new DatatypeManager(new ServiceManager()));
        }

        return $this->datatypeManager;
    }

    /**
     * Create a form
     *
     * @param  array $spec
     * @return ZFElementInterface
     */
    public function createForm($spec)
    {
        if (!isset($spec['type'])) {
            $spec['type'] = Form::class;
        }

        return $this->create($spec);
    }

    /**
     * Create an element, fieldset, or form
     *
     * Introspects the 'type' key of the provided $spec, and determines what
     * type is being requested; if none is provided, assumes the spec
     * represents simply an element.
     *
     * @param  array|Traversable $spec
     * @return ElementInterface
     * @throws \Zend\Form\Exception\DomainException
     */
    public function create($spec)
    {
        $spec = $this->validateSpecification($spec, __METHOD__);
        $type = isset($spec['type']) ? $spec['type'] : Element::class;
        $spec['factory'] = new Factory();

        $element = $this->getDatatypeManager()->get($type);

        if ($element instanceof FormInterface) {
            return $this->configureForm($element, $spec);
        }

        if ($element instanceof FieldsetInterface) {
            return $this->configureFieldset($element, $spec);
        }

        if ($element instanceof ZFElementInterface) {
            return $this->configureElement($element, $spec);
        }

        throw new Exception\DomainException(sprintf(
            '%s expects the $spec["type"] to implement one of %s, %s, or %s; received %s',
            __METHOD__,
            ElementInterface::class,
            FieldsetInterface::class,
            FormInterface::class,
            $type
        ));
    }
}