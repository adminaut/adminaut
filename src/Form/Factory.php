<?php

namespace Adminaut\Form;

use Adminaut\Datatype\DatatypeManager\DatatypeManagerV3Polyfill;
use Traversable;
use Zend\Form\Element;
use Zend\Form\ElementInterface as ZFElementInterface;
use Zend\Form\ElementInterface;
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
     * @var DatatypeManagerV3Polyfill
     */
    private $datatypeManager;

    /**
     * Set the form element manager
     *
     * @param  DatatypeManagerV3Polyfill $datatypeManager
     * @return Factory
     */
    public function setDatatypeManager(DatatypeManagerV3Polyfill $datatypeManager)
    {
        $this->datatypeManager = $datatypeManager;
        return $this;
    }

    /**
     * Get form element manager
     *
     * @return DatatypeManagerV3Polyfill
     */
    public function getDatatypeManager()
    {
        if ($this->datatypeManager === null) {
            $this->setDatatypeManager(new DatatypeManagerV3Polyfill(new ServiceManager()));
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

        throw new \Zend\Form\Exception\DomainException(sprintf(
            '%s expects the $spec["type"] to implement one of %s, %s, or %s; received %s',
            __METHOD__,
            ElementInterface::class,
            FieldsetInterface::class,
            FormInterface::class,
            $type
        ));
    }

    /**
     * Configure a form based on the provided specification
     *
     * Specification follows that of {@link configureFieldset()}, and adds the
     * following keys:
     *
     * - input_filter: input filter instance, named input filter class, or
     *   array specification for the input filter factory
     * - hydrator: hydrator instance or named hydrator class
     *
     * @param  FormInterface                  $form
     * @param  array|Traversable|ArrayAccess  $spec
     * @return FormInterface
     */
    public function configureForm(FormInterface $form, $spec)
    {
        $spec = $this->validateSpecification($spec, __METHOD__);
        /** @var Form $form */
        $form = parent::configureForm($form, $spec);

        if (isset($spec['widgets'])) {
            $form->setWidgets($spec['widgets']);
        }

        return $form;
    }
}