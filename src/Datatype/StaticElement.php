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

    /**
     * @var string
     */
    protected $title;

    public function setOptions($options)
    {
        if(isset($options['label'])) {
            $this->title = $options['label'];
            unset($options['label']);
        }

        if(isset($options['title'])) {
            $this->title = $options['title'];
        }

        $this->datatypeSetOptions($options);
    }

    // todo: temporary show delimiter between sections
    public function getValue()
    {
        return '<h3 class="static-element">' . $this->title . '</h3>';
    }
}
