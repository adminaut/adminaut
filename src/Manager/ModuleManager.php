<?php

namespace Adminaut\Manager;

use Adminaut\Datatype\MultiReference;
use Adminaut\Datatype\Reference;
use Adminaut\Form\Annotation\AnnotationBuilder;
use Adminaut\Form\Element\CyclicSheet;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use DoctrineModule\Form\Element\ObjectRadio;
use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Adminaut\Entity\BaseEntityInterface;
use Adminaut\Entity\UserInterface;
use Adminaut\Mapper\ModuleMapper;
use Adminaut\Options\ModuleOptions;
use Adminaut\Form\Form;
use Zend\Form\Fieldset;

/**
 * Class ModuleManager
 * @package Adminaut\Manager
 */
class ModuleManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ModuleOptions
     */
    private $moduleOptions;

    /**
     * @var ModuleMapper
     */
    private $mapper;

    /**
     * @var
     */
    private $form;

    /**
     * ModuleManager constructor.
     * @param EntityManager $entityManager
     * @param ModuleOptions $moduleOptions
     */
    public function __construct(EntityManager $entityManager, ModuleOptions $moduleOptions)
    {
        $this->entityManager = $entityManager;
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @return array
     */
    public function getList($criteria = null)
    {
        return $this->getMapper()->getList($criteria);
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
     * @return string
     */
    public function getEntityClass()
    {
        return $this->getModuleOptions()->getEntityClass();
    }

    /**
     * @param $form
     * @param UserInterface $user
     * @return mixed
     */
    public function addEntity($form, UserInterface $user, BaseEntityInterface $parentEntity = null)
    {
        $entityClass = $this->options->getEntityClass();
        /* @var $entity BaseEntityInterface */
        $entity = new $entityClass();
        $entity->setInsertedBy($user->getId());
        $entity->setUpdatedBy($user->getId());
        $entity = $this->bind($entity, $form, $parentEntity);
        return $this->getMapper()->insert($entity);
    }

    /**
     * @param BaseEntityInterface $entity
     * @param Form $form
     * @param $user
     * @return mixed
     */
    public function updateEntity(BaseEntityInterface $entity, Form $form, UserInterface $user, BaseEntityInterface $parentEntity = null)
    {
        $entity->setUpdatedBy($user->getId());
        $entity = $this->bind($entity, $form, $parentEntity);
        return $this->getMapper()->update($entity);
    }

    /**
     * @param BaseEntityInterface $entity
     * @param UserInterface $user
     * @return mixed
     */
    public function deleteEntity(BaseEntityInterface $entity, UserInterface $user)
    {
        $entity->setDeleted(true);
        $entity->setDeletedBy($user->getId());
        return $this->getMapper()->update($entity);
    }

    /**
     * @param BaseEntityInterface $entity
     * @param Form $form
     * @return BaseEntityInterface
     */
    public function bind(BaseEntityInterface $entity, Form $form, BaseEntityInterface $parentEntity = null)
    {
        /* @var $element Element */
        foreach ($form->getElements() as $element) {
            $elementName = $element->getName();
            if ($elementName === 'reference_property') {
                if ($element->getValue() === 'parentId') {
                    $entity->{$element->getValue()} = $parentEntity->getId();
                } else {
                    $entity->{$element->getValue()} = $parentEntity;
                }
                continue;
            }

            if (method_exists($element, 'getInsertValue')) {
                $entity->{$elementName} = $element->getInsertValue();
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
        $entityClass = $this->getModuleOptions()->getEntityClass();
        $builder = new AnnotationBuilder();

        /**
         * @var $form Form
         */
        $form = $builder->createForm(new $entityClass());
        $form->setHydrator(new DoctrineObject($this->getEntityManager()));

        if (isset($this->getOptions()->getLabels()['general_tab'])) {
            $tabs = $form->getTabs();
            $tabs['main']['label'] = $this->getOptions()->getLabels()['general_tab'];
            $form->setTabs($tabs);
        }

        /** @var Fieldset[] $fieldsets */
        $fieldsets = [];

        /** @var ObjectSelect|ObjectRadio|ObjectMultiCheckbox|CyclicSheet $element */
        foreach ($form->getElements() as $element) {
            if ($element instanceof ObjectSelect ||
                $element instanceof ObjectRadio ||
                $element instanceof ObjectMultiCheckbox ||
                $element instanceof Reference ||
                $element instanceof MultiReference) {
                $element->setOption('object_manager', $this->getEntityManager());
            } else if ($element instanceof CyclicSheet) {
                $form->addTab($element->getName(), [
                    'label' => $element->getLabel(),
                    'action' => 'cyclicSheetAction',
                    'entity' => $element->getTargetClass(),
                    'referencedProperty' => $element->getReferencedProperty(),
                    'readonly' => $element->isReadonly(),
                    'active' => false,
                ]);

                $form->remove($element->getName());
                continue;
            }

            if (method_exists($element, 'isPrimary')) {
                if ($element->isPrimary()) {
                    $form->setPrimaryField($element->getName());
                }
            } else if ($element->getOption('primary') === true) {
                $form->setPrimaryField($element->getName());
            }


            /*if($tab = $element->getOption("tab")) {
                if($tab != "General") {
                    $filter = new \Zend\Filter\StripTags();
                    $tabName = $filter->filter($tab);

                    if(!isset($fieldsets[$tab])) {
                        $fieldsets[$tab] = new Fieldset($tab);
                    }

                    $fieldsets[$tab]->add($element);
                    $form->addTab($tab, [
                        'label' => $tab,
                        'action' => 'formTab'
                    ]);
                    $form->remove($element->getName());
                }
            }*/
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
     * @return ModuleOptions
     * @deprecated
     */
    public function getOptions()
    {
        return $this->getModuleOptions();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }
}
