<?php

namespace Adminaut\Authentication\Helper;

use Zend\Crypt\Hash;
use Zend\Math\Rand;

/**
 * Class AccessTokenHelper
 * @package Adminaut\Authentication\Helper
 */
class AccessTokenHelper
{
    /**
     * @param int $length
     * @return string
     */
    public static function generate($length = 128)
    {
        return Rand::getString((int)$length);
    }

    /**
     * @param string $accessToken
     * @param string $algorithm
     * @return string
     */
    public static function hash($accessToken, $algorithm = 'sha512')
    {
        return Hash::compute((string)$algorithm, (string)$accessToken);
    }
}
