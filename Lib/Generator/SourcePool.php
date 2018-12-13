<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 29.
 * Time: 7:41
 */

namespace Lib\Generator;


use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

class SourcePool
{

    private static $sourceFiles = [];

    /**
     * @param array $file Consists of path and content keys
     */
    public static function addSourceFile($file)
    {
        /**
         * Key generation based on the following assumptions:
         * - one source file belongs to only one namespace
         * - one source file contains only one class
         */
        $namespace = current($file['content']->getNamespaces())->getName();
        $class = current(current($file['content']->getNamespaces())->getClasses())->getName();
        $FQName = '\\' . $namespace . '\\' . $class;

        if (array_key_exists($FQName, self::$sourceFiles)) {
            self::$sourceFiles[$FQName] = array_merge(self::$sourceFiles[$FQName], $file);
        } else {
            self::$sourceFiles[$FQName] = $file;
        }
    }

    public static function addImplements($classFQName, $interfaceName)
    {
        if (!is_array($interfaceName)) {
            $interfaceName = [$interfaceName];
        }

        if (!array_key_exists($classFQName, self::$sourceFiles)) {
            self::$sourceFiles[$classFQName] = [];
        }

        if (!array_key_exists('implements', self::$sourceFiles[$classFQName])) {
            self::$sourceFiles[$classFQName]['implements'] = [];
        }

        self::$sourceFiles[$classFQName]['implements'] = array_merge(self::$sourceFiles[$classFQName]['implements'], $interfaceName);
    }

    private static function mergeImplements()
    {
        foreach (self::$sourceFiles as $classFQName => $sourceFile) {
            if (array_key_exists('implements', $sourceFile)) {
                foreach (array_unique($sourceFile['implements']) as $implement) {
                    $class = current(current($sourceFile['content']->getNamespaces())->getClasses());
                    // Todo 03: add "use" to class in order to use the short name in implemented interface
                    $class->addImplement('Wheel\\Concept\\' . $implement);

                    $interfaceFile = new PhpFile();
                    $interfaceNamespace = $interfaceFile->addNamespace('Wheel\\Concept');
                    $interfaceClass = $interfaceNamespace->addInterface($implement);

                    self::addSourceFile([
                        'path' => 'Wheel/Concept/' . $implement . '.php',
                        'content' => $interfaceFile
                    ]);

                }
            }
        }
    }

    /**
     *
     */
    public static function dump()
    {
        self::mergeImplements();

        // todo 03: delete concept directory first to avoid from keeping deprecated files from previous code generation
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

    public static function debug()
    {
//        var_dump(self::$sourceFiles);
        foreach (self::$sourceFiles as $class => $sourceFile) {
//            var_dump($class);
        }
    }

}