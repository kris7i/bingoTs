<?php

namespace Tools;

/**
 * 时间处理
 * Class Time
 * @package lzqqdy\tools
 */
class Time
{
    const YEAR = 31536000;
    const MONTH = 2592000;
    const WEEK = 604800;
    const DAY = 86400;
    const HOUR = 3600;
    const MINUTE = 60;

    /**
     * php将时间处理成“xx时间前”
     *
     * @param $time
     *
     * @return string
     */
    public static function formatTime($time)
    {
        $t = time() - $time;
        $f = [
            '31536000' => '年',
            '2592000'  => '个月',
            '604800'   => '星期',
            '86400'    => '天',
            '3600'     => '小时',
            '60'       => '分钟',
            '1'        => '秒',
        ];
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v . '前';
            }
        }
    }

    /**
     * 获取本周所有日期
     * @param string $time
     * @param string $format
     * @return array
     */
    public static function get_week($time = '', $format = 'Y-m-d')
    {
        $time = $time != '' ? $time : time();
        //获取当前周几
        $week = date('w', $time);
        $date = [];
        for ($i = 1; $i <= 7; $i++) {
            $date[$i] = date($format, strtotime('+' . $i - $week . ' days', $time));
        }
        return $date;
    }

    /**
     * 获取最近七天所有日期
     * @param string $time
     * @param string $format
     * @return array
     */
    public static function get_weeks($time = '', $format = 'Y-m-d')
    {
        $time = $time != '' ? $time : time();
        //组合数据
        $date = [];
        for ($i = 1; $i <= 7; $i++) {
            $date[$i] = date($format, strtotime('+' . $i - 7 . ' days', $time));
        }
        return $date;
    }

    /**
     * 计算两个时区间相差的时长,单位为秒
     *
     * $seconds = self::offset('America/Chicago', 'GMT');
     *
     * [!!] A list of time zones that PHP supports can be found at
     * <http://php.net/timezones>.
     *
     * @param   string $remote timezone that to find the offset of
     * @param   string $local timezone used as the baseline
     * @param   mixed $now UNIX timestamp or date string
     * @return  integer
     */
    public static function offset($remote, $local = null, $now = null)
    {
        if ($local === null) {
            // Use the default timezone
            $local = date_default_timezone_get();
        }
        if (is_int($now)) {
            // Convert the timestamp into a string
            $now = date(DateTime::RFC2822, $now);
        }
        // Create timezone objects
        $zone_remote = new DateTimeZone($remote);
        $zone_local = new DateTimeZone($local);
        // Create date objects from timezones
        $time_remote = new DateTime($now, $zone_remote);
        $time_local = new DateTime($now, $zone_local);
        // Find the offset
        $offset = $zone_remote->getOffset($time_remote) - $zone_local->getOffset($time_local);
        return $offset;
    }

    /**
     * 计算两个时间戳之间相差的时间
     *
     * $span = self::span(60, 182, 'minutes,seconds'); // array('minutes' => 2, 'seconds' => 2)
     * $span = self::span(60, 182, 'minutes'); // 2
     *
     * @param   int $remote timestamp to find the span of
     * @param   int $local timestamp to use as the baseline
     * @param   string $output formatting string
     * @return  string   when only a single output is requested
     * @return  array    associative list of all outputs requested
     * @from https://github.com/kohana/ohanzee-helpers/blob/master/src/Date.php
     */
    public static function span($remote, $local = null, $output = 'years,months,weeks,days,hours,minutes,seconds')
    {
        // Normalize output
        $output = trim(strtolower((string)$output));
        if (!$output) {
            // Invalid output
            return false;
        }
        // Array with the output formats
        $output = preg_split('/[^a-z]+/', $output);
        // Convert the list of outputs to an associative array
        $output = array_combine($output, array_fill(0, count($output), 0));
        // Make the output values into keys
        extract(array_flip($output), EXTR_SKIP);
        if ($local === null) {
            // Calculate the span from the current time
            $local = time();
        }
        // Calculate timespan (seconds)
        $timespan = abs($remote - $local);
        if (isset($output['years'])) {
            $timespan -= self::YEAR * ($output['years'] = (int)floor($timespan / self::YEAR));
        }
        if (isset($output['months'])) {
            $timespan -= self::MONTH * ($output['months'] = (int)floor($timespan / self::MONTH));
        }
        if (isset($output['weeks'])) {
            $timespan -= self::WEEK * ($output['weeks'] = (int)floor($timespan / self::WEEK));
        }
        if (isset($output['days'])) {
            $timespan -= self::DAY * ($output['days'] = (int)floor($timespan / self::DAY));
        }
        if (isset($output['hours'])) {
            $timespan -= self::HOUR * ($output['hours'] = (int)floor($timespan / self::HOUR));
        }
        if (isset($output['minutes'])) {
            $timespan -= self::MINUTE * ($output['minutes'] = (int)floor($timespan / self::MINUTE));
        }
        // Seconds ago, 1
        if (isset($output['seconds'])) {
            $output['seconds'] = $timespan;
        }
        if (count($output) === 1) {
            // Only a single output was requested, return it
            return array_pop($output);
        }
        // Return array
        return $output;
    }

    /**
     * 计算两个时间戳之间相差的时间
     *
     * @param $begin_time
     * @param $end_time
     * @return array
     */
    public static function timeDiff($begin_time, $end_time)
    {
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        //计算天数
        $timediff = $endtime - $starttime;
        $days = intval($timediff / 86400);
        //计算小时数
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);
        //计算分钟数
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        //计算秒数
        $secs = $remain % 60;
        $res = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
        return $res;
    }

    /**
     * 获取当天是本月的第几周
     * 不是我写的
     * 生成當月   日期對應第幾周數組
     */
    public static function dateWeek($date)
    {
        $time = strtotime($date);
        $year = date('Y');
        $month = date('m');
        $days = date('t', $time);
        $arr = array();
        static $week = 1;
        for ($i = 1; $i <= $days; $i++) {
            $t = strtotime($year . '-' . $month . '-' . $i);
            $d = date('d', $t);
            $w = date('w', $t);
            $w == 6 ? $arr[$d] = $week++ : $arr[$d] = $week;;
        }
        return $arr;
    }

    //echo  monthWeek(date('Ymd'));
    public static function monthWeek($date)
    {
        $time = strtotime($date);
        $d = date('d', $time);
        $arr = self::dateWeek($date);
        return $arr[$d];
    }


    /**
     * 显示刚刚、几分钟前、几小时前、几天前的函数
     * @param $posttime 时间：2017-08-12 18:18:18
     * @return string
     */
    public static function time_ago($posttime){
        //当前时间的时间戳
        $nowtimes = strtotime(date('Y-m-d H:i:s'),time());
        //之前时间参数的时间戳
        $posttimes = strtotime($posttime);
        //相差时间戳
        $counttime = $nowtimes - $posttimes;
        //进行时间转换
        if($counttime<=10){
            return '刚刚';
        }else if($counttime>10 && $counttime<=30){
            return '刚才';
        }else if($counttime>30 && $counttime<=60){
            return '刚一会';
        }else if($counttime>60 && $counttime<=120){
            return '1分钟前';
        }else if($counttime>120 && $counttime<=180){
            return '2分钟前';
        }else if($counttime>180 && $counttime<3600){
            return intval(($counttime/60)).'分钟前';
        }else if($counttime>=3600 && $counttime<3600*24){
            return intval(($counttime/3600)).'小时前';
        }else if($counttime>=3600*24 && $counttime<3600*24*2){
            return '昨天';
        }else if($counttime>=3600*24*2 && $counttime<3600*24*3){
            return '前天';
        }else if($counttime>=3600*24*3 && $counttime<=3600*24*20){
            return intval(($counttime/(3600*24))).'天前';
        }else{
            return $posttime;
        }
    }
    /**
     * [isleap 是否是闰年]
     * @desc
     * @author limx
     * @param null $time 时间戳、DateTime时间字符串
     * @return bool
     */
    public static function isleap($time = NULL)
    {
        empty($time) && $time = time();
        is_string($time) && $time = strtotime($time);
        $year = date('Y', $time);
        if ($year % 400 === 0) return true;
        if ($year % 4 === 0 && $year % 100 !== 0) return true;
        return false;
    }
}
