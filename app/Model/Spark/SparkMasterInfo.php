<?php

namespace App\Model\Spark;

use Illuminate\Database\Eloquent\Model;

class SparkMasterInfo
{

    public function __construct(){

    }

    public $url;
    public $workers;
    public $aliveworkers;
    public $cores;
    public $coresused;
    public $memory;
    public $memoryused;
    public $activeapps;
    public $completedapps;
    public $status;


    public function getMasterIp(){
        if ($this->url != ''){
            $prefix = 'spark://';
            $suffix = ':7077';
            $ip = str_replace( $prefix, '', $this->url);
            $ip = str_replace( $suffix, '', $ip);
            return $ip;
        }
        return;
    }
}
