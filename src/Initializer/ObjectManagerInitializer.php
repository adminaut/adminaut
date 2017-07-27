<?php

namespace Adminaut\Initializer;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

/**
 * Class ObjectManagerInitializer
 * @package Adminaut\Initializer
 */
class ObjectManagerInitializer
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ObjectManagerInitializer constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $element
     * @param $formElements
     */
    public function __invoke($element, $formElements)
    {
        if ($element instanceof ObjectManagerAwareInterface) {
            $element->setObjectManager($this->entityManager);
        }
    }
}
