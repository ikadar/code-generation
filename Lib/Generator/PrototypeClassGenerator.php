<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 29.
 * Time: 7:53
 */

namespace Lib\Generator;

use Nette\PhpGenerator\PhpFile;

/**
 * Class PrototypeClassGenerator
 *
 * todo: this class should extend ClassGenerator class, but currently there are conflicts between static and non-static variables of these classes
 *
 * @package Lib\Generator
 */

class PrototypeClassGenerator
{

    private static $prototypeClassSource;
    private static $nameSpace;
    private static $class;
    private static $initializer;
    private static $keyInitializer;
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

        self::$keyInitializer = self::$class->addMethod("initKeys")->setVisibility('private')->setStatic();
        self::$keyInitializer->addBody('self::$prototypes = [];');

        self::$initializer = self::$class->addMethod("init")->setVisibility('public')->setStatic();
        self::$initializer->addBody('self::initKeys();');

        $instantiateMethod = self::$class->addMethod("new");
        $instantiateMethod->setStatic();
        // todo: move this into parent class
        $instantiateMethod->addParameter('className')->setTypeHint('string');
        $instantiateMethod->addBody('if (array_key_exists($className, self::$prototypes)) {');
        $instantiateMethod->addBody('    if (self::$prototypes[$className] === null) {');
        $instantiateMethod->addBody('        self::$prototypes[$className] = new $className();');
        $instantiateMethod->addBody('    }');
        $instantiateMethod->addBody('    return clone self::$prototypes[$className];');
        $instantiateMethod->addBody('} else {');
        $instantiateMethod->addBody('    throw new \\Exception($className . \' has no prototype.\');');
        $instantiateMethod->addBody('}');
    }

    public static function addClass($classFQName)
    {
        self::$keyInitializer->addBody('self::$prototypes["' . $classFQName . '"] = null;');
        self::$initializer->addBody('self::$prototypes["' . $classFQName . '"] = new ' . $classFQName . '();');
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