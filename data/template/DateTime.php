<?php

namespace Application\Entity;

use Adminaut\Entity\AdminautEntityTrait;
use Adminaut\Entity\AdminautEntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class Datetime
 * @ORM\Entity()
 * @ORM\Table(name="datetime")
 * @property integer $id
 * @package Application\Entity
 */
class DateTime implements AdminautEntityInterface
{
    use AdminautEntityTrait;

    /**
     * @ORM\Column(type="date");
     * @Annotation\Options({"label":"Date"});
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Form\Element\Date");
     */
    protected $date;
    /**
     * @ORM\Column(type="datetime");
     * @Annotation\Options({"label":"DateTime"});
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Form\Element\DateTime");
     */
    protected $datetime;
    /**
     * @ORM\Column(type="time");
     * @Annotation\Options({"label":"Time"});
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Form\Element\Time");
     */
    protected $time;
}
