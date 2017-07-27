<?php

namespace Adminaut\Datatype;

use Traversable;

/**
 * Class Text
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
