<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Base
 * @package Adminaut\Entity
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 * @deprecated Use trait AdminautEntityTrait and implement AdminautEntityInterface
 */
class Base implements AdminautEntityInterface
{
    use AdminautEntityTrait;
}
