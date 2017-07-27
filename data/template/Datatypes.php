<?php

namespace Application\Entity;

use Adminaut\Entity\Base;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class Datatypes
 * @ORM\Entity()
 * @ORM\Table(name="Datatypes")
 * @property integer $id
 * @package Application\Entity
 */
class Datatypes extends Base
{
    /**
     * @ORM\Column(type="date");
     * @Annotation\Options({"label":"Date"});
     * @Annotation\Required(true);
     * @Annotation\Type("MfccAdminModule\Form\Element\Date");
     */
//    protected $date;

    /**
     * @ORM\Column(type="datetime");
     * @Annotation\Options({"label":"DateTime"});
     * @Annotation\Required(true);
     * @Annotation\Type("MfccAdminModule\Form\Element\DateTime");
     */
//    protected $datetime;

    /**
     * @ORM\Column(type="integer");
     * @Annotation\Attributes({"min":0, "max":1000});
     * @Annotation\Options({"label":"Number"});
     * @Annotation\Required(true);
     * @Annotation\Type("Zend\Form\Element\Number");
     */
//    protected $number;

    /**
     * @ORM\Column(type="string", length=255);
     * @Annotation\Options({"label":"Password"});
     * @Annotation\Required(true);
     * @Annotation\Validator({"name": "StringLength", "options": {"min":6, "max": 255}})
     * @Annotation\Type("Zend\Form\Element\Password");
     */
//    protected $password;

    /**
     * @ORM\Column(type="string", length=255);
     * @Annotation\Options({"label":"Text", "listed":true});
     * @Annotation\Required(true);
     * @Annotation\Validator({"name": "StringLength", "options": {"min":5, "max": 255}})
     * @Annotation\Type("Zend\Form\Element\Text");
     */
    protected $text;

    /**
     * @ORM\Column(type="text");
     * @Annotation\Options({"label":"Textarea"});
     * @Annotation\Required(true);
     * @Annotation\Validator({"name": "NotEmpty"})
     * @Annotation\Type("Zend\Form\Element\Textarea");
     */
//    protected $textarea;

    /**
     * @ORM\Column(type="time");
     * @Annotation\Attributes({"min":"00:00:00", "max":"23:59:59"});
     * @Annotation\Options({"label":"Time", "format":"H:i"});
     * @Annotation\Required(true);
     * @Annotation\Type("MfccAdminModule\Form\Element\Time");
     */
//    protected $time;

    /**
     * @ORM\Column(type="string", length=255);
     * @Annotation\Options({"label":"Url", "listed":true});
     * @Annotation\Attributes({"value":"http://"});
     * @Annotation\Required(true);
     * @Annotation\Type("Zend\Form\Element\Url");
     */
    protected $url;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\News")
     * @ORM\JoinColumn(name="reference", referencedColumnName="id", nullable=false)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Options({
     *   "label":"Reference",
     *   "required":"true",
     *   "empty_option": "Select reference",
     *   "target_class": "Application\Entity\News",
     *   "property": "title"
     * })
     */
//    protected $reference;

    /**
     * @ORM\OneToOne(targetEntity="MfccAdminModule\Entity\File")
     * @ORM\JoinColumn(name="file", referencedColumnName="id", nullable=true)
     * @Annotation\Options({"label":"File"});
     * @Annotation\Type("MfccAdminModule\Form\Element\File");
     */
//    protected $file;

    /**
     * @ORM\OneToOne(targetEntity="MfccAdminModule\Entity\File")
     * @ORM\JoinColumn(name="image", referencedColumnName="id", nullable=true)
     * @Annotation\Options({"label":"Image","required":true});
     * @Annotation\Type("MfccAdminModule\Form\Element\Image");
     */
    protected $image;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $file
     */
    public function setImage($file)
    {
        $this->image = $file;
    }
}
