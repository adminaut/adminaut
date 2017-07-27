<?php

namespace Application\Entity;

use Adminaut\Entity\Base;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class Datetime
 * @ORM\Entity()
 * @ORM\Table(name="datetime")
 * @property integer $id
 * @package Application\Entity
 */
class DateTime extends Base
{
    /**
     * @ORM\Column(type="date");
     * @Annotation\Options({"label":"Date"});
     * @Annotation\Required(true);
     * @Annotation\Type("MfccAdminModule\Form\Element\Date");
     */
    protected $date;
    /**
     * @ORM\Column(type="datetime");
     * @Annotation\Options({"label":"DateTime"});
     * @Annotation\Required(true);
     * @Annotation\Type("MfccAdminModule\Form\Element\DateTime");
     */
    protected $datetime;
    /**
     * @ORM\Column(type="time");
     * @Annotation\Options({"label":"Time"});
     * @Annotation\Required(true);
     * @Annotation\Type("MfccAdminModule\Form\Element\Time");
     */
    protected $time;
}
