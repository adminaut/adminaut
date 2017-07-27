<?php

namespace Adminaut\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Class Octal
 * @package Adminaut\Validator
 */
class Octal extends AbstractValidator
{
    /**
     * const
     */
    const NOT_OCTAL = 'notOctal';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_OCTAL => "The input is not octal",
    ];

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        if (decoct(octdec($value)) != $value) {
            $this->error(self::NOT_OCTAL);
            return false;
        }
        return true;
    }
}
