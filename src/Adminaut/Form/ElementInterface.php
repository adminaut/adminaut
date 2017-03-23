<?php

namespace Adminaut\Form;

use Adminaut\Entity\BaseInterface;

/**
 * Interface ElementInterface
 * @package Adminaut\Form
 */
interface ElementInterface extends \Zend\Form\ElementInterface
{
    /**
     * @return mixed
     */
    public function getListedValue();
}
