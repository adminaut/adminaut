<?php

namespace Adminaut\Manager;

use Adminaut\Form\Element\CyclicSheet;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use DoctrineModule\Form\Element\ObjectRadio;
use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Adminaut\Entity\BaseInterface;
use Adminaut\Entity\UserInterface;
use Adminaut\Form\Element;
use Adminaut\Mapper\ModuleMapper;
use Adminaut\Options\ModuleOptions;
use Adminaut\Form\Form;
use Adminaut\Form\Annotation\AnnotationBuilder;
use Zend\Form\Element\Radio;

/**
 * Class ModuleManager
 * @package Adminaut\Manager
 */
class ModuleManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var ModuleMapper
     */
    protected $mapper;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var
     */
    protected $form;

    /**
     * AdminModuleManager constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->setEntityManager($em);
    }
    
    /**
     * @return array
     */
    public function getList()
    {
        return $this->getMapper()->getList();
    }

    /**
     * @param $entityId
     * @return object
     */
    public function findById($entityId)
    {
        return $this->getMapper()->findById($entityId);
    }

    /**
     * @param $form
     * @param UserInterface $user
     * @return mixed
     */
    public function addEntity($form, UserInterface $user)
    {
        $entityClass = $this->options->getEntityClass();
        /* @var $entity BaseInterface */
        $entity = new $entityClass();
        $entity->setInsertedBy($user->getId());
        $entity->setUpdatedBy($user->getId());
        $entity = $this->bind($entity, $form);
        return $this->getMapper()->insert($entity);
    }

    /**
     * @param BaseInterface $entity
     * @param Form $form
     * @param $user
     * @return mixed
     */
    public function updateEntity(BaseInterface $entity, Form $form, UserInterface $user)
    {
        $entity->setUpdatedBy($user->getId());
        $entity = $this->bind($entity, $form);
        return $this->getMapper()->update($entity);
    }

    /**
     * @param BaseInterface $entity
     * @param UserInterface $user
     * @return mixed
     */
    public function deleteEntity(BaseInterface $entity, UserInterface $user)
    {
        $entity->setDeleted(true);
        $entity->setDeletedBy($user->getId());
        return $this->getMapper()->update($entity);
    }

    /**
     * @param BaseInterface $entity
     * @param Form $form
     * @return BaseInterface
     */
    public function bind(BaseInterface $entity, Form $form)
    {
        /* @var $element Element */
        foreach ($form->getElements() as $element) {
            $elementName = $element->getName();

            if (method_exists($element, 'getInsertedValue')) {
                $entity->{$elementName} = $element->getInsertedValue();
            } else {
                $entity->{$elementName} = $element->getValue();
            }
        }
        return $entity;
    }
    
    /**
     * @return Form
     */
    public function getForm()
    {
        if ($this->form instanceof Form) {
            return $this->form;
        } else {
            return $this->createForm();
        }
    }

    /**
     * @param $form
     * @return Form
     */
    public function setForm($form)
    {
        if ($form instanceof Form) {
            return $this->form = $form;
        } else {
            throw new Exception\RuntimeException(
                'Param $form must be instance of Zend\Form\Form.'
            );
        }
    }

    /**
     * @return Form
     */
    public function createForm()
    {
        $entityClass = $this->options->getEntityClass();
        $builder = new AnnotationBuilder($this->getEntityManager());

        /**
         * @var $form Form
         */
        $form = $builder->createForm(new $entityClass());
        $form->setHydrator(new DoctrineObject($this->getEntityManager()));

        /** @var ObjectSelect|ObjectRadio|ObjectMultiCheckbox|CyclicSheet $element */
        foreach($form->getElements() as $element){
            if($element instanceof ObjectSelect ||
            $element instanceof ObjectRadio ||
            $element instanceof ObjectMultiCheckbox) {
                $element->setOption('object_manager', $this->getEntityManager());
            } elseif($element instanceof CyclicSheet) {
                $form->addTab($element->getName(), [
                    'label' => $element->getLabel(),
                    'action' => 'cyclicSheetAction',
                    'entity' => $element->getOption('target_class'),
                    'active' => false
                ]);

                $form->remove($element->getName());
            }
        }

        return $form;
    }

    /**
     * @return ModuleMapper
     */
    public function getMapper()
    {
        if (null === $this->mapper) {
            throw new Exception\RuntimeException(
                'ModuleMapper not set.'
            );
        }
        return $this->mapper;
    }

    /**
     * @param ModuleMapper $mapper
     * @return $this
     */
    public function setMapper(ModuleMapper $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * @return ModuleOptions
     */
    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            throw new Exception\RuntimeException(
                'ModuleOptions not set.'
            );
        }
        return $this->options;
    }

    /**
     * @param ModuleOptions $options
     */
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEntityManager($em)
    {
        $this->em = $em;
    }
}