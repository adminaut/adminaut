<?php
namespace Adminaut\Initializer;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\Form\FormElementManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ObjectManagerInitializer
{
    public function __invoke($element, $formElements) {
        if ($element instanceof ObjectManagerAwareInterface) {
            /** @var ServiceLocatorInterface $serviceLocator */
            $serviceLocator = $formElements->getServiceLocator();
            $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

            $element->setObjectManager($entityManager);
        }
    }
}