<?php

namespace Tools;

/**
 * 生成接口数据格式
 * //测试
 * $grade = array("
 *  score" => array(70, 95, 70.0, 60, "70"),
 *  "name" => array("Zhang San", "Li Si", "Wang Wu", "Zhao Liu", "TianQi")
 *  );
 * $response = new Response();
 * $result = $response :: show(200,'success',$grade,'json');
 * print_r($result);
 */
class Response
{
    /**
     * [show 按综合方式输出数据]
     * @param [int] $code    [状态码]
     * @param [string] $message [提示信息]
     * @param array $data [数据]
     * @param [string] $type [类型]
     * @return [string]    [返回值]
     */
    public static function show($code, $message, $data = array(), $type = '')
    {
        if (!is_numeric($code)) {
            return '';
        }
        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        if ($type == 'json') {
            return self::json($code, $message, $data);
        } elseif ($type == 'xml') {
            return self::xml($code, $message, $data);
        } else {
        }
    }

    /**
     * [json 按json方式输出数据]
     * @param [int] $code    [状态码]
     * @param [string] $message [提示信息]
     * @param [array] $data  [数据]
     * @return [string]     [返回值]
     */
    public static function json($code, $message, $data = array())
    {
        if (!is_numeric($code)) {
            return '';
        }
        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        $result = json_encode($result);
        return $result;
    }

    /**
     * [xml 按xml格式生成数据]
     * @param [int] $code    [状态码]
     * @param [string] $message [提示信息]
     * @param array $data [数据]
     * @return [string]     [返回值]
     */
    public static function xml($code, $message, $data = array())
    {
        if (!is_numeric($code)) {
            return '';
        }
        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";
        $xml .= self::xmlToEncode($data);
        $xml .= "</root>";
        return $xml;
    }

    public static function xmlToEncode($data)
    {
        $xml = '';
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $attr = "id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key} {$attr}>\n";
            $xml .= is_array($value) ? self::xmlToEncode($value) : "{$value}\n";
            $xml .= "</{$key}>\n";
        }
        return $xml;
    }
}