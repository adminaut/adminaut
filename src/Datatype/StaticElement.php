<?php

namespace Adminaut\Datatype;

/**
 * Class StaticElement
 * @package Adminaut\Datatype
 */
class StaticElement extends \TwbBundle\Form\Element\StaticElement
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    // todo: temporary show delimiter between sections
    public function getValue()
    {
        return '<b>----- ' . $this->getLabel() . ' ------</b>';
    }
}
