<?php

namespace Adminaut\Form;

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
