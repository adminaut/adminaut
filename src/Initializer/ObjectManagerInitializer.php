<?php

namespace Adminaut\Initializer;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**+
 * Class ObjectManagerInitializer
 * @package Adminaut\Initializer
 */
class ObjectManagerInitializer
{

    /**
     * @param $element
     * @param $formElements
     * todo: can we make it as factory?
     */
    public function __invoke($element, $formElements)
    {
        if ($element instanceof ObjectManagerAwareInterface) {
            /** @var ServiceLocatorInterface $serviceLocator */
            $serviceLocator = $formElements->getServiceLocator();
            $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

            $element->setObjectManager($entityManager);
        }
    }
}