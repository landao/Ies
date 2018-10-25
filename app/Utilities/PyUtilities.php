<?php

namespace App\Utilities;

use App\Model\Spark\SparkMasterInfo;

class PyUtilities
{

    const MOUNT = '_mount';

    const MASTER = '_master';

    const SLAVE = '_slave';

    public static function getIpFromString($text)
    {
        $result = "";
        if ($text != "") {
            $pos = strpos($text, self::MOUNT);
            if ($pos === false) {
                $pos = strpos($text, self::MASTER);
                if ($pos === false) {
                    $pos = strpos($text, self::SLAVE);
                    if ($pos === false) {
                        $result = "";
                    } else {
                        $ip     = str_replace(self::SLAVE, '', $text);
                        $result = "Device/" . $ip . " start py park slave service successfully.";
                        return $result;
                    }

                } else {
                    $ip     = str_replace(self::MASTER, '', $text);
                    $result = "Device/" . $ip . " start py park master service successfully.";
                    return $result;
                }

            } else {
                $ip     = str_replace(self::MOUNT, '', $text);
                $result = "Device/" . $ip . " mount to network SSD successfully.";
                return $result;
            }

        }
    }

    public static function parseIpDiscovery($input)
    {
        $result  = '';
        $nasTerm = ': NAS';
        if ($input != '') {
            $pos = strpos($input, $nasTerm);
            if ($pos === false) {
                $result = $input;
            } else {
                $result = str_replace($nasTerm, '', $input);
            }

        }

        return $result;
    }

    public static function isNASDevice($term)
    {
        $nasTerm = ': NAS';
        if ($term != '') {
            $pos = strpos($term, $nasTerm);
            if ($pos === false) {
                return 0;
            } else {
                return 1;
            }

        }

        return 0;
    }


    public static function getNasIp($ipList){
        if (is_array($ipList) && count($ipList) > 0){
            $nasTerm = ': NAS';
            foreach ($ipList as $key => $value) {
                if (self::isNASDevice($value) == 1){
                    return str_replace($nasTerm , '', $value);
                }
            }
        }
        return;
    }

    public static function joinedToCluster(SparkMasterInfo $cluster, $term)
    {
        $master;
        if ($cluster->url != '' && $term != '') {
            $pos = strpos($cluster->url, $term);
            if ($pos === false) {
                $master = 0;
            } else {
                return 1;
            }

            if (is_array($cluster->workers) == true) {
                foreach ($cluster->workers as $key) {
                    $pos = strpos($key['host'], $term);
                    if ($pos === false ) {
                        $master = 0;
                    } else if ( $key['state'] === 'ALIVE') {
                        return 1;
                    }
                }
            }
        }
        return 0;

    }

    public static function isMasterIp($term)
    {
        if ($term != '') {
            if ($term == env('SPARK_MASTER', '')) {
                return 1;
            }
        }
        return 0;
    }

    public static function streamConfigtoJson($cmd)
    {

        if ($cmd != '' && strlen($cmd) > 0) {
            $cmd = str_replace('\/', '/', $cmd);
            $cmd = str_replace('\\', '', $cmd);
            $cmd = str_replace('"["', '[', $cmd);
            $cmd = str_replace('"]"', ']', $cmd);
            $cmd = str_replace('}","{', '},{', $cmd);
            $cmd = str_replace('_', '-', $cmd);
        }

        return $cmd;
    }

    public static function inArray($arrayPrefix, $term)
    {
        if ($term != '' && is_array($arrayPrefix) == true) {
            foreach ($arrayPrefix as $value) {

                if (strpos($term, $value) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function contain($key, $term)
    {
        if ($key != '' && $term != '') {
            if (strpos($term, $key) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function getStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini    = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }

        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public static function createSparkUrl($ip){
        return 'spark://'.$ip.':7077';
    }

    public static function createSparkMasterJson($ip){
        $port = '8080';
        return 'http://'.$ip.':'. $port . '/json';
    }

    public static function createSparkMasterJson81($ip){
        $port = '8081';
        return 'http://'.$ip.':'. $port . '/json';
    }


}
