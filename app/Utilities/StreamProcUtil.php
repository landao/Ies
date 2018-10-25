<?php
namespace App\Utilities;

use App\Model\Spark\StreamLogFileModel;
use App\Model\Spark\StreamLogDateModel;
use App\Utilities\PyUtilities;
use Karriere\JsonDecoder\JsonDecoder;

class StreamProcUtil
{

    public static function hostIpParse($hostName)
    {
        $result = array();
        if ($hostName != '') {
            $items = explode(',', $hostName);

            if (is_array($items) == true && count($items) > 0) {
                for ($i = 0; $i < count($items); $i++) {

                    $li = trim($items[$i]);

                    if ($li != '' && PyUtilities::contain(':', $li) === true) {

                        $ipPort = explode(':', $li);
                        if (is_array($ipPort) && count($ipPort) == 2) {
                            if (self::is_ip($ipPort[0]) == true && intval($ipPort[1]) > 0) {
                                $result[] = array('ip' => $ipPort[0] . ':' . intval($ipPort[1]));
                            }

                        }

                    }

                }
            }

        }
        return $result;
    }

    public static function is_ip($str)
    {
        $ret = filter_var($str, FILTER_VALIDATE_IP);
        return $ret;
    }

    public static function is_ipv4($str)
    {
        $ret = filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

        return $ret;
    }

    public static function is_ipv6($str)
    {
        $ret = filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

        return $ret;
    }

    public static function parseFileLogCond($logCond)
    {
        if ($logCond != '' && strlen($logCond) > 0) {
            $jsonDecoder = new JsonDecoder();
            $logCond     = trim(preg_replace('/\s\s+/', ' ', $logCond));
            $logObj      = $jsonDecoder->decode('{' . $logCond . '}', StreamLogFileModel::class);
            return array('file_log' => $logObj) ;
        }
        return array('file_log' => new StreamLogFileModel);

    }


    public static function parseDateLogCond($logCond)
    {
        if ($logCond != '' && strlen($logCond) > 0) {
            $jsonDecoder = new JsonDecoder();
            $logCond     = trim(preg_replace('/\s\s+/', ' ', $logCond));
            $logObj      = $jsonDecoder->decode('{' . $logCond . '}', StreamLogDateModel::class);
            return array('date_log' => $logObj) ;
        }
        return array('file_log' =>  new StreamLogDateModel);

    }

}
