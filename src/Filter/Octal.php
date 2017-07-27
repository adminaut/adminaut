<?php

namespace Adminaut\Filter;

use Adminaut\Exception;
use Adminaut\Validator as FileManagerValidator;

use Zend\Filter\AbstractFilter;
use Zend\Validator as ZendValidator;

/**
 * Class Octal
 * @package Adminaut\Filter
 */
class Octal extends AbstractFilter
{
    /**
     * const
     */
    const NOT_DIGITS = 1;
    const NOT_OCTAL = 2;

    /**
     * @var \Zend\Validator\Digits
     */
    protected static $validatorDigits = null;

    /**
     * @var \Adminaut\Validator\Octal
     */
    protected static $validatorOctal = null;

    /**
     * {@inheritDoc}
     * @param mixed $value
     * @return int|mixed
     */
    public function filter($value)
    {
        // Value is already an integer ; nothing to do
        if (is_int($value)) {
            return $value;
        }

        if (null === static::$validatorDigits) {
            static::$validatorDigits = new ZendValidator\Digits();
        }

        if (!static::$validatorDigits->isValid($value)) {
            throw new Exception\InvalidArgumentException(
                'Cannot filter Octal value',
                self::NOT_DIGITS,
                new ZendValidator\Exception\InvalidArgumentException(implode(' ; ', static::$validatorDigits->getMessages()))
            );
        }

        if (null === static::$validatorOctal) {
            static::$validatorOctal = new FileManagerValidator\Octal();
        }

        if (!static::$validatorOctal->isValid($value)) {
            throw new Exception\InvalidArgumentException(
                'Cannot filter Octal value',
                self::NOT_OCTAL,
                new Exception\InvalidArgumentException(implode(' ; ', static::$validatorOctal->getMessages()))
            );
        }
        return intval($value, 8);
    }
}