<?php

namespace Tools;

class Usual
{
    public static function sayHello()
    {
        return 'Hello World!';
    }

    /**
     * 打印调试函数
     * @param $content
     * @param $is_die
     */
    public static function pre($content, $is_die = true)
    {
        header('Content-type: text/html; charset=utf-8');
        echo '<pre>' . print_r($content, true);
        $is_die && die();
    }




}