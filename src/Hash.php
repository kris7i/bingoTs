<?php

namespace Tools;

class Hash
{
    /**
     * hash加密
     * @param $code
     * @return bool|string
     */
    public static function keyWHash($code)
    {
        return $hash = password_hash($code, PASSWORD_DEFAULT);
    }


    /**
     * hash解密
     * @param $code
     * @param $hashCode
     * @return bool
     */
    public static function keyWHashCheck($code, $hashCode)
    {
        if (password_verify($code, $hashCode)) {
            return true;
        } else {
            return false;
        }
    }
}