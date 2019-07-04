<?php

namespace Tools;

class Log
{
    /**
     * [write 写日志]
     * @desc
     * @author limx
     * @param string $content 日志内容 Arr 或 String
     * @param string $code 标识
     * @param string $root 根目录
     * @param string $file 文件名
     */
    public static function write($content = '', $code = 'LOG', $root = '', $file = '')
    {
        empty($root) && $root = 'log/' . date('Ym') . '/';
        substr($root, -1) != '/' && $root .= '/';
        empty($file) && $file = date('Ymd');
        $file .= '.log';
        if (!is_dir($root)) {
            mkdir($root, 0777, true);
        }
        $msg[] = date('Y-m-d H:i:s');
        $msg[] = strtoupper($code);
        $msg[] = is_array($content) ? json_encode($content) : $content;
        $info = implode('|', $msg);
        file_put_contents($root . $file, $info . "\n", FILE_APPEND);
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