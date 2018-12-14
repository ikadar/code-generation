<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 10:52
 */

namespace Lib;


class Util
{
    /**
     * Transforms an under_scored_string to a camelCasedOne
     */
    static function camelize($scored)
    {
        return lcfirst(
            implode(
                '',
                array_map(
                    'ucfirst',
                    explode(
                        '_', $scored))));
//        return lcfirst(
//            implode(
//                '',
//                array_map(
//                    'ucfirst',
//                    array_map(
//                        'strtolower',
//                        explode(
//                            '_', $scored)))));
    }

    /**
     * Transforms a camelCasedString to an under_scored_one
     */
    static function snakeize($cameled)
    {
        return implode(
            '_',
            array_map(
                'strtolower',
                preg_split('/([A-Z]{1}[^A-Z]*)/', $cameled, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY)));
    }

    /**
     * Transforms a camelCasedString to an under_scored_one
     */
    static function pascalize($string)
    {
        return ucfirst(self::camelize($string));
    }

    public static function getAutoGenConceptNS(): string
    {
        return implode('\\', [\Configuration::get('WHEEL_NAMESPACE'), \Configuration::get('AUTO_CONCEPT_NAMESPACE')]);
    }

    public static function getAutoGenNS(): string
    {
        return implode('\\', [\Configuration::get('WHEEL_NAMESPACE'), \Configuration::get('AUTO_NAMESPACE')]);
    }

    public static function getCoreNS(): string
    {
        return implode('\\', [\Configuration::get('WHEEL_NAMESPACE'), \Configuration::get('CORE_NAMESPACE')]);
    }

    public static function addTrailingAutoGenConceptNS(string $className): string
    {
        return implode('\\', [self::getAutoGenConceptNS(), $className]);
    }

    public static function addTrailingAutoGenNS(string $className): string
    {
        return implode('\\', [self::getAutoGenNS(), $className]);
    }

    public static function addTrailingCoreNS(string $className): string
    {
        return implode('\\', [self::getCoreNS(), $className]);
    }

    public static function FQNameToPath(string $FQName, string $directorySeparator = '/'): string
    {
        $pathElements = explode('\\', $FQName);
        return implode($directorySeparator, $pathElements) . '.php';
    }

}