<?php

require __DIR__ . '/vendor/autoload.php';

class Configuration
{
    public static $configuration = [

        /**
         * If you change WHEEL_NAMESPACE key, then you have to change composer too:
         *
         *   "autoload": {
         *     "psr-4": {
         *       "Wheel\\": "Wheel/",
         */

        "WHEEL_NAMESPACE" => "Wheel",
        "AUTO_NAMESPACE" => "Auto",
//        "AUTO_CONCEPT_NAMESPACE" => "Concept",
        "AUTO_CONCEPT_NAMESPACE" => "Auto\\Concept",
        "CONCEPT_NAMESPACE" => "Concept",
        "CORE_NAMESPACE" => "Core",
    ];

    public static function get($key)
    {
        if (array_key_exists($key, self::$configuration)) {
            return self::$configuration[$key];
        }

        throw new \Exception('Configuration key ' . $key . ' doesn\'t exist.');
    }
}
