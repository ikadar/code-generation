<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 29.
 * Time: 7:41
 */

namespace Lib\Generator;


class SourcePool
{

    private static $sourceFiles = [];

    /**
     * @param $file
     */
    public static function addSourceFile($file)
    {
        self::$sourceFiles[] = $file;
    }

    /**
     *
     */
    public static function dump()
    {
        // todo: delete concept directory first to avoid from keeping deprecated files from previous code generation
        foreach (self::$sourceFiles as $sourceFile) {
            self::dumpFile($sourceFile['path'], $sourceFile['content']);
        }
    }

    /**
     * @param $path
     * @param $content
     */
    private static function dumpFile($path, $content)
    {
        $filePath = explode('/', $path);
        $pathElements = [];
        foreach ($filePath as $loop => $pathItem) {
            $pathElements[] = $pathItem;
            $currentPath = realpath('.') . '/' . implode('/', $pathElements);
            if ($loop < count($filePath) - 1) {
                if (!file_exists($currentPath)) {
                    mkdir($currentPath, 0777, true);
                }
            } else {
                file_put_contents($currentPath, $content);
            }
        }
//        echo("\n");
//
//        echo $currentPath;
//        echo "\n";
//        echo $content;

    }

}