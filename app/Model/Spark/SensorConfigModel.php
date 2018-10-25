<?php

namespace App\Model\Spark;

use Illuminate\Database\Eloquent\Model;

class SensorConfigModel
{
    public $sensor;
    public $sensorID;
    public $condition;
    public $value;
    public $publish_mqtt;
    public $mqtt_topic;
    public $mqtt_payload;
}
