<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Base
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @package Adminaut\Entity
 * @deprecated Use trait AdminautEntity
 */
class Base implements AdminautEntityInterface, BaseEntityInterface
{
    use AdminautEntityTrait;
}
