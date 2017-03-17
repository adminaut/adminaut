<?php
namespace Adminaut\Form;


use Adminaut\Form\Form;
use Zend\Form\ElementInterface as ZFElementInterface;

class Factory extends \Zend\Form\Factory
{
    /**
     * Create a form
     *
     * @param  array $spec
     * @return ZFElementInterface
     */
    public function createForm($spec)
    {
        if (!isset($spec['type'])) {
            $spec['type'] = Form::class;
        }

        return $this->create($spec);
    }
}