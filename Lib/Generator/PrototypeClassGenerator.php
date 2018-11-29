<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 29.
 * Time: 7:53
 */

namespace Lib\Generator;

use Nette\PhpGenerator\PhpFile;

class PrototypeClassGenerator
{

    private static $prototypeClassSource;
    private static $nameSpace;
    private static $class;
    private static $constructor;
    private static $pathElements;

    public static function initialize(array $pathElements) {

        self::$pathElements = $pathElements;
        array_push(self::$pathElements, 'PrototypeService');
        array_push(self::$pathElements, 'php');

        self::$prototypeClassSource = new PhpFile();
        self::$nameSpace = self::$prototypeClassSource->addNamespace(GeneratorPathBuilderService::buildNameSpace(self::$pathElements));

        self::$class = self::$nameSpace
            ->addClass('PrototypeService')
//            ->setExtends($this->parentClass)
//            ->addComment($this->getClassDocBlock())
        ;
        self::$class->setFinal();

        self::$class->addProperty('prototypes')->setStatic()->setVisibility('protected');

        self::$constructor = self::$class->addMethod("__construct")->setVisibility('public');
        self::$constructor->addBody('self::$prototypes = [];');

        $getter = self::$class->addMethod("get");
        $getter->setStatic();
        $getter->addParameter('className')->setTypeHint('string');
        $getter->addBody('return clone self::$prototypes[$className];');
    }

    public static function addClass($classFQName)
    {
        self::$constructor->addBody('self::$prototypes["' . $classFQName . '"] = new \\' . $classFQName . '();');
    }

    public static function getSource(): PhpFile
    {
        return self::$prototypeClassSource;
    }

    public static function getPath()
    {
        return GeneratorPathBuilderService::buildPath(self::$pathElements);
    }
}