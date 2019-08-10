<?php

namespace Tools;

/**
 * Xml处理
 * Class xml
 * @package lzqqdy\tools
 */
class Xml
{
    /**
     * 将xml转为array
     *
     * @param string $xml
     * return array
     */
    public static function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

}