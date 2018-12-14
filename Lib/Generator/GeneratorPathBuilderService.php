<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 10:49
 */

namespace Lib\Generator;

use Lib\Util;

/**
 * Class GeneratorPathBuilderService
 *
 * Simple helper methods to build class related path-like strings. E.g. {concept}\{rubric}\{attribute}
 *
 * @package Lib\Generator
 */
class GeneratorPathBuilderService
{

    /**
     * @param $pathElements
     * @return array
     */
    public static function getConceptRootRelativePathElements($pathElements)
    {
        $conceptRootDirElements = explode('\\', Util::getAutoGenConceptNS());
        return array_merge($conceptRootDirElements, $pathElements);
    }

    /**
     * @param $pathElements
     * @param string $directorySeparator
     * @return array|string
     */
    public static function buildPath($pathElements, $directorySeparator = '/')
    {
        $extension = array_pop($pathElements);

        $path = array_map(function ($item) {
            return Util::pascalize($item);
        }, $pathElements);
        $path = implode($directorySeparator, $path);
        $path = implode('.', [$path, $extension]);
        return $path;
    }

    /**
     * @param $pathElements
     * @param string $directorySeparator
     * @return array|string
     */
    public static function buildNameSpace($pathElements, $directorySeparator = '\\')
    {
        array_pop($pathElements); // extension
        array_pop($pathElements); // file name

        $path = array_map(function ($item) {
            return Util::pascalize($item);
        }, $pathElements);
        $path = implode($directorySeparator, $path);
        return $path;
    }

    public static function getNameSpace($pathElements)
    {
        array_pop($pathElements); // extension
        array_pop($pathElements); // file name

        return $pathElements;
    }

    /**
     * @param $pathElements
     * @param string $directorySeparator
     * @return array|string
     */
    public static function buildUse($pathElements, $directorySeparator = '\\')
    {
        array_pop($pathElements); // extension

        $path = array_map(function ($item) {
            return Util::pascalize($item);
        }, $pathElements);
        $path = implode($directorySeparator, $path);

        return $path;
    }

    /**
     * @param $pathElements
     * @return string
     */
    public static function getClassName($pathElements)
    {
        array_pop($pathElements); // extension
        $fileName = array_pop($pathElements);
        return Util::pascalize($fileName);
    }

    /**
     * @param $pathElements
     * @param string $directorySeparator
     * @return array|string
     */
    public static function buildFQName($pathElements, $directorySeparator = '\\')
    {
        array_pop($pathElements); // extension

        $path = array_map(function ($item) {
            return Util::pascalize($item);
        }, $pathElements);
        $path = implode($directorySeparator, $path);
        return $directorySeparator . $path;
    }

}