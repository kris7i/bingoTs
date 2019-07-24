<?php

namespace Tools;

/**
 * 随机
 * Class Random
 * @package lzqqdy\tools
 */
class Random
{
    /**
     * 生成UUID
     *
     * @return string
     */
    public static function uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff), mt_rand(0, 0x0fff) |
            0x4000, mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * 生成不重复的随机数
     *
     * @param  int $start 需要生成的数字开始范围
     * @param  int $end 结束范围
     * @param  int $length 需要生成的随机数个数
     *
     * @return array 生成的随机数
     */
    public static function get_rand_number($start = 1, $end = 10, $length = 4)
    {
        $count = 0;
        $temp = [];
        while ($count < $length) {
            $temp[] = mt_rand($start, $end);
            $data = array_unique($temp);
            $count = count($data);
        }
        sort($data);
        return $data;
    }

    /**
     * 生成随机颜色
     *
     * @return string
     */
    public static function randomColor()
    {
        $str = '#';
        for ($i = 0; $i < 6; $i++) {
            $randNum = rand(0, 15);
            switch ($randNum) {
                case 10:
                    $randNum = 'A';
                    break;
                case 11:
                    $randNum = 'B';
                    break;
                case 12:
                    $randNum = 'C';
                    break;
                case 13:
                    $randNum = 'D';
                    break;
                case 14:
                    $randNum = 'E';
                    break;
                case 15:
                    $randNum = 'F';
                    break;
            }
            $str .= $randNum;
        }
        return $str;
    }

    /**
     * 生成订单号
     * @return string
     */
    public static function createOrderNum()
    {
        return date('Ymd') . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 生成随机密码
     * @param int $length 长度
     * @param string $chars 字符集
     * @return string
     */
    public static function getRandPass($length = 6, $chars = '')
    {
        $password = '';

        $chars ?: $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $char_len = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $loop = mt_rand(0, ($char_len - 1));
            $password .= $chars[$loop];
        }

        return $password;
    }

    /**
     * 生成手机验证码
     * @return string
     */
    public static function rand_captcha()
    {
        $key = '';
        $pattern = '1234567890';
        for ($i = 0; $i < 6; $i++) {
            $key .= $pattern[mt_rand(0, 9)];
        }
        return $key;
    }

    /**
     * 生成随机字符串，数字，大小写字母随机组合
     *
     * @param int $length 长度
     * @param int $type 类型，1 纯数字，2 纯小写字母，3 纯大写字母，4 数字和小写字母，5 数字和大写字母，6 大小写字母，7 数字和大小写字母
     */
    public static function random($length = 6, $type = 1)
    {
        // 取字符集数组
        $number = range(0, 9);
        $lowerLetter = range('a', 'z');
        $upperLetter = range('A', 'Z');
        // 根据type合并字符集
        if ($type == 1) {
            $charset = $number;
        } elseif ($type == 2) {
            $charset = $lowerLetter;
        } elseif ($type == 3) {
            $charset = $upperLetter;
        } elseif ($type == 4) {
            $charset = array_merge($number, $lowerLetter);
        } elseif ($type == 5) {
            $charset = array_merge($number, $upperLetter);
        } elseif ($type == 6) {
            $charset = array_merge($lowerLetter, $upperLetter);
        } elseif ($type == 7) {
            $charset = array_merge($number, $lowerLetter, $upperLetter);
        } else {
            $charset = $number;
        }
        $str = '';
        // 生成字符串
        for ($i = 0; $i < $length; $i++) {
            $str .= $charset[mt_rand(0, count($charset) - 1)];
            // 验证规则
            if ($type == 4 && strlen($str) >= 2) {
                if (!preg_match('/\d+/', $str) || !preg_match('/[a-z]+/', $str)) {
                    $str = substr($str, 0, -1);
                    $i = $i - 1;
                }
            }
            if ($type == 5 && strlen($str) >= 2) {
                if (!preg_match('/\d+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                    $str = substr($str, 0, -1);
                    $i = $i - 1;
                }
            }
            if ($type == 6 && strlen($str) >= 2) {
                if (!preg_match('/[a-z]+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                    $str = substr($str, 0, -1);
                    $i = $i - 1;
                }
            }
            if ($type == 7 && strlen($str) >= 3) {
                if (!preg_match('/\d+/', $str) || !preg_match('/[a-z]+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                    $str = substr($str, 0, -2);
                    $i = $i - 2;
                }
            }
        }
        return $str;
    }
}