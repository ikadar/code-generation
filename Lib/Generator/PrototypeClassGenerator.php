<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 29.
 * Time: 7:53
 */

namespace Lib\Generator;

use Lib\Util;
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
    private static $pathElements;
    private static $assignmentsArray;

    public static function initialize()
    {
        $pathElements = explode('\\', Util::getAutoGenNS());

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

        $prototypesArray = self::$class->addProperty('prototypes')->setStatic()->setVisibility('protected');
        $prototypesArray->setValue([]);

        self::$assignmentsArray = self::$class->addProperty('assignments')->setStatic()->setVisibility('protected');
        self::$assignmentsArray->setValue([]);

        $instantiateMethod = self::$class->addMethod("new");
        $instantiateMethod->setStatic();
        // todo 02: move this into parent class
        $instantiateMethod->addParameter('className')->setTypeHint('string');
        $instantiateMethod->addBody('if (array_key_exists($className, self::$assignments)) {');
        $instantiateMethod->addBody('    if (!array_key_exists($className, self::$prototypes)) {');
        $instantiateMethod->addBody('        self::$prototypes[$className] = new self::$assignments[$className]();');
        $instantiateMethod->addBody('    }');
        $instantiateMethod->addBody('    return clone self::$prototypes[$className];');
        $instantiateMethod->addBody('} else {');
        $instantiateMethod->addBody('    throw new \\Exception($className . \' has no prototype.\');');
        $instantiateMethod->addBody('}');
    }

    public static function addClass($classFQName)
    {
        $prototypeKey = array_filter(explode('\\', $classFQName));
        array_shift($prototypeKey); // remove "Wheel"
        array_shift($prototypeKey); // remove "Auto"
        array_shift($prototypeKey); // remove "Concept"
        $prototypeKey = ('\\' . implode('\\', $prototypeKey));

        $assignments = self::$assignmentsArray->getValue();
        $assignments[$prototypeKey] = $classFQName;
        self::$assignmentsArray->setValue($assignments);

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