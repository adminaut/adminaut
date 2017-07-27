<?php

namespace Adminaut\Authentication\Helper;

use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

/**
 * Class PasswordHelper
 * @package Adminaut\Authentication\Helper
 */
class PasswordHelper
{
    /**
     * @param int $length
     * @param bool $specialChars
     * @return string
     */
    public static function generate($length = 8, $specialChars = false)
    {
        if (true === $specialChars) {
            return Rand::getString($length);
        }

        $charList = implode(array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9')));
        return Rand::getString($length, $charList);
    }

    /**
     * @param string $password
     * @param int $cost
     * @return string
     */
    public static function hash($password, $cost = 10)
    {
        return (new Bcrypt())->setCost((int)$cost)->create((string)$password);
    }

    /**
     * @param string $password
     * @param string $passwordHash
     * @return bool
     */
    public static function verify($password, $passwordHash)
    {
        return (new Bcrypt())->verify((string)$password, (string)$passwordHash);
    }
}
