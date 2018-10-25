<?php

namespace App\Model\Spark;

use Illuminate\Database\Eloquent\Model;
use Karriere\JsonDecoder\ClassBindings;
use Karriere\JsonDecoder\Bindings\FieldBinding;
use Karriere\JsonDecoder\Bindings\AliasBinding;
use Karriere\JsonDecoder\Transformer;


class SensorTransformer implements Transformer
{
    public function register(ClassBindings $classBindings)
    {
        //$classBindings->register(new FieldBinding('publish_mqtt', 'publish-mqtt',"string"));
        $classBindings->register(new AliasBinding('publish_mqtt', 'publish-mqtt'));
        $classBindings->register(new AliasBinding('mqtt_topic', 'mqtt-topic'));
        $classBindings->register(new AliasBinding('mqtt_payload', 'mqtt-payload'));
       
    }

    public function transforms()
    {
        return SensorConfigModel::class;
    }
}