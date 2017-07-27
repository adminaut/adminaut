<?php

namespace Application\Entity;

use Adminaut\Entity\Base;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class Basic
 * @ORM\Entity()
 * @ORM\Table(name="news")
 * @property integer $id
 * @package Application\Entity
 */
class News extends Base
{
    /**
     * @ORM\Column(type="string", length=255);
     * @Annotation\Options({"label":"Name", "listed":true});
     * @Annotation\Required(true);
     * @Annotation\Validator({"name": "StringLength", "options": {"min":1, "max": 255}})
     * @Annotation\Type("Zend\Form\Element\Text");
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255);
     * @Annotation\Options({"label":"Perex", "listed":false});
     * @Annotation\Required(true);
     * @Annotation\Validator({"name": "StringLength", "options": {"min":1, "max": 255}})
     * @Annotation\Type("Zend\Form\Element\Text");
     * @var string
     */
    protected $perex;
}
