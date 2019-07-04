<?php

namespace Tools;

/**
 * 其他函数
 * Class Other
 * @package lzqqdy\tools
 */
class Other
{
    /**
     * 由经纬度算距离
     *
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     *
     * @return float
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //地球半径
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }

    /**
     * 获取用户IP地址
     * @return mixed
     */
    public static function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * 获取服务器ip地址
     * @return string
     */
    public static function serverIP()
    {
        return gethostbyname($_SERVER["SERVER_NAME"]);
    }

    /**
     *
     * @param  $latitude    纬度
     * @param  $longitude   经度
     * @param  $raidus      半径范围(单位：米)
     * @return multitype:number
     */
    public static function getAround($latitude, $longitude, $raidus)
    {
        $PI = 3.14159265;
        $degree = (24901 * 1609) / 360.0;
        $dpmLat = 1 / $degree;
        $radiusLat = $dpmLat * $raidus;
        $minLat = $latitude - $radiusLat;
        $maxLat = $latitude + $radiusLat;
        $mpdLng = $degree * cos($latitude * ($PI / 180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng * $raidus;
        $minLng = $longitude - $radiusLng;
        $maxLng = $longitude + $radiusLng;
        return array('minLat' => $minLat, 'maxLat' => $maxLat, 'minLng' => $minLng, 'maxLng' => $maxLng);
    }

    /**
     *参数说明：
     *$lng  经度
     *$lat   纬度
     * $distance  周边半径  默认是500米（0.5Km）
     */
    public static function returnSquarePoint($lng, $lat, $distance = 0.5)
    {
        $dlng = 2 * asin(sin($distance / (2 * 6371)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance / 6371;
        $dlat = rad2deg($dlat);
        return array(
            'left-top' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right-top' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );
    }
}