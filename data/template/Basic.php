<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use MfccAdminModule\Entity\Base;

use Zend\Form\Annotation;

/**
 * Class Basic
 * @ORM\Entity()
 * @ORM\Table(name="basic")
 * @property integer $id
 * @package Application\Entity
 */
class Basic extends Base
{
    /**
     * @ORM\Column(type="string", length=128);
     * @Annotation\Options({"label":"Name", "listed":true});
     * @Annotation\Required(true);
     * @Annotation\Validator({"name": "StringLength", "options": {"min":3, "max": 128}})
     * @Annotation\Type("Zend\Form\Element\Text");
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=128);
     * @Annotation\Options({"label":"Email", "listed":true});
     * @Annotation\Type("Zend\Form\Element\Email");
     * @var string
     */
    protected $email;
}