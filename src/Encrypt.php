<?php

namespace Tools;

class Encrypt
{
    /**
     * 加密
     * @param array/string $string
     * @param int $member_id
     * @return mixed arrray/string
     */
    public static function buyEncrypt($string, $member_id)
    {
        $buy_key = sha1(md5($member_id . '&' . MD5_KEY));
        if (is_array($string)) {
            $string = serialize($string);
        } else {
            $string = strval($string);
        }
        return self::encrypt(base64_encode($string), $buy_key);
    }

    /**
     * 解密
     * @param $string
     * @param $member_id
     * @param int $ttl
     * @return bool|mixed|string|void
     */
    public static function buyDecrypt($string, $member_id, $ttl = 0)
    {
        $buy_key = sha1(md5($member_id . '&' . MD5_KEY));
        if (empty($string)) return;
        $string = base64_decode(self::decrypt(strval($string), $buy_key, $ttl));
        return ($tmp = @unserialize($string)) !== false ? $tmp : $string;
    }

    public static function encrypt($txt, $key)
    {
        $encrypt_key = md5(mt_rand(0, 100));
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            if ($ctr == strlen($encrypt_key))
                $ctr = 0;
            $tmp .= substr($encrypt_key, $ctr, 1) . (substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1));
            $ctr++;
        }
        return self::keyED($tmp, $key);
    }

    public static function decrypt($txt, $key)
    {
        $txt = self::keyED($txt, $key);
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = substr($txt, $i, 1);
            $i++;
            $tmp .= (substr($txt, $i, 1) ^ $md5);
        }
        return $tmp;
    }

    public static function keyED($txt, $encrypt_key) //定义一个keyED
    {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            if ($ctr == strlen($encrypt_key))
                $ctr = 0;
            $tmp .= substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1);
            $ctr++;
        }
        return $tmp;
    }


    // 加密url参数
    public static function encrypt_url($url, $key)
    {
        return rawurlencode(base64_encode(self::encrypt($url, $key)));
    }

    // 解密url参数
    public static function decrypt_url($url, $key)
    {
        return self::decrypt(base64_decode(rawurldecode($url)), $key);
    }


    /**
     * 加密数组变成字符串
     * @param array $data
     * @param string $key
     * @return string
     */
    public static function getEncryptChr($ArrayData = [], $key = '')
    {
        $parms = '';
        if (!empty($ArrayData)) {
            foreach ($ArrayData as $k => $v) {
                if (count($ArrayData) > 1) {
//                $v = (is_array($v) && empty($v))?'null':implode(",", $v);
                    if (is_array($v) && empty($v)) {
                        $v = '';
                    } elseif (is_array($v) && !empty($v)) {
                        $v = implode(",", $v);
                    }
                    $parms .= $k . '=' . $v . "&";
                } else {
                    $parms .= $k . '=' . $v;
                }
            }
        }
        return self::encrypt_url($parms . "&time=" . time(), $key);
    }

    /**
     * 加密字符串解密变成数组
     * @param string $str
     * @param string $key
     * @return array
     */
    public static function getDecryptData($str = '', $key = '')
    {
        $vars = [];
        $str = self::decrypt_url($str, $key);
        $url_array = explode('&', $str);
        if (is_array($url_array)) {
            foreach ($url_array as $var) {
                if (!empty($var)) {
                    $var_array = explode('=', $var);
                    $vars[$var_array[0]] = $var_array[1];
                }
            }
        }
        return $vars;
    }


    /**
     * [encode 加密]
     * @author limx
     * @param $string
     * @param string $key
     * @return mixed
     */
    public static function encode($string, $key = '')
    {
        $key = md5($key);
        $key_length = strlen($key);
        $string = substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        return str_replace('=', '', base64_encode($result));
    }
    /**
     * [decode 解密]
     * @desc
     * @author limx
     * @param $string
     * @param string $key
     * @return string
     */
    public static function decode($string, $key = '')
    {
        $key = md5($key);
        $key_length = strlen($key);
        $string = base64_decode($string);
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
            return substr($result, 8);
        } else {
            return '';
        }
    }
}