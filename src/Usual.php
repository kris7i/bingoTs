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

    /**
     * 写入日志
     * @param string|array $values
     * @param string $dir
     * @return bool|int
     */
    public static function write_log($values, $dir)
    {
        if (is_array($values))
            $values = print_r($values, true);
        // 日志内容
        $content = '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL . $values . PHP_EOL . PHP_EOL;
        try {
            // 文件路径
            $filePath = $dir . '/logs/';
            // 路径不存在则创建
            !is_dir($filePath) && mkdir($filePath, 0755, true);
            // 写入文件
            return file_put_contents($filePath . date('Ymd') . '.log', $content, FILE_APPEND);
        } catch (\Exception $e) {
            return false;
        }
    }



}