<?php

namespace Adminaut\Authentication\Helper;

use Zend\Crypt\Password\BcryptSha;

/**
 * Class PasswordHelper
 * @package Adminaut\Authentication\Helper
 */
class PasswordHelper
{
    /**
     * @param string $password
     * @param int $cost
     * @return string
     */
    public static function hash($password, $cost = 10)
    {
        return (new BcryptSha())->setCost((int)$cost)->create((string)$password);
    }

    /**
     * @param string $password
     * @param string $passwordHash
     * @return bool
     */
    public static function verify($password, $passwordHash)
    {
        return (new BcryptSha())->verify((string)$password, (string)$passwordHash);
    }
}
