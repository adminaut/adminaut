<?php

namespace Adminaut\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Class Module
 * @package Adminaut\Form
 */
class Module extends Form
{
    /**
     * Module constructor.
     */
    public function __construct()
    {
        parent::__construct($name = null, $options = []);
    }
}