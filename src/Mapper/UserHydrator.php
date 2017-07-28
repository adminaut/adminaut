<?php

namespace Adminaut\Mapper;

use Zend\Hydrator\ClassMethods;
use Adminaut\Entity\UserInterface as UserEntityInterface;

/**
 * Class UserHydrator
 * @package Adminaut\Mapper
 */
class UserHydrator extends ClassMethods
{
    /**
     * @param object $object
     * @return array
     */
    public function extract($object)
    {
        if (!$object instanceof UserEntityInterface) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Adminaut\Entity\UserInterface');
        }
        $data = parent::extract($object);
        if ($data['id'] !== null) {
            $data = $this->mapField('id', 'user_id', $data);
        } else {
            unset($data['id']);
        }
        return $data;
    }

    /**
     * @param array $data
     * @param object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof UserEntityInterface) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Adminaut\Entity\UserInterface');
        }
        $data = $this->mapField('id', 'id', $data);
        return parent::hydrate($data, $object);
    }

    /**
     * @param $keyFrom
     * @param $keyTo
     * @param array $array
     * @return array
     */
    protected function mapField($keyFrom, $keyTo, array $array)
    {
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);
        return $array;
    }
}
