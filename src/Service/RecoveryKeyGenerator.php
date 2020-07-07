<?php declare(strict_types=1);


namespace Adminaut\Service;


use Zend\Math\Rand;

/**
 * Class RecoverKeyGenerator
 */
class RecoveryKeyGenerator
{
    /**
     * @param int $length
     * @return string
     */
    public static function generate(int $length = 32): string
    {
        $charList = implode(array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9')));

        return Rand::getString($length, $charList);
    }
}