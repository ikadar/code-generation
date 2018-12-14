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
 * Class ClassVarGenerator
 *
 * Add class member variable to class:
 * - use statement
 * - class variable declaration
 * - class variable instantiation into rubric constructor
 * - setter for class variable
 * - getter for class variable
 *
 * @package Lib
 */
class ClassVarGenerator
{

    const USE_STATEMENT = 'use';
    const DECLARATION = 'declaration';
    const INSTANTIATION = 'instantiation';
    const SETTER = 'setter';
    const GETTER = 'getter';

    /**
     * @param $hostClass
     * @param $name
     * @param $type
     * @param $alias
     * @param $pathElements
     * @param $partsToCreate
     * @param null $setterBodyCallback
     * @param null $getterBodyCallback
     */
    public static function addClassVar($hostClass, $name, $type, $alias, $pathElements, $partsToCreate, $setterBodyCallback = null, $getterBodyCallback = null)
    {
        $alias = $alias ? Util::pascalize($alias) : AttributeClassGenerator::getAttributeAlias($pathElements);
        $setterBodyCallback = $setterBodyCallback ?: function ($attributeName) {
            return [
                '$this->' . $attributeName . ' = $' . $attributeName . ';'
            ];
        };

        $getterBodyCallback = $getterBodyCallback ?: function ($attributeName) {
            return [
                'return $this->' . $attributeName . ';'
            ];
        };

        /**
         * Create use statement for attribute
         */
        if (in_array(self::USE_STATEMENT, $partsToCreate)) {
            self::addUseStatement($hostClass, $alias, $pathElements);
        }

        /**
         * Create attribute declaration
         */
        if (in_array(self::DECLARATION, $partsToCreate)) {
            self::addAttributeDeclaration($hostClass, $name, $alias);
        }

        /**
         * Create code for attribute instantiation
         */
        if (in_array(self::INSTANTIATION, $partsToCreate)) {
            self::addAttributeInstantiation($hostClass, $name, $alias, $pathElements);
        }

        /**
         * Create setter for attribute value
         */
        if (in_array(self::SETTER, $partsToCreate)) {
            self::addAttributeSetter($hostClass, $name, $type, $pathElements, $setterBodyCallback);
        }

        /**
         * Create getter for attribute value
         */
        if (in_array(self::GETTER, $partsToCreate)) {
            self::addAttributeGetter($hostClass, $name, $type, $pathElements, $getterBodyCallback);
        }

    }

    protected static function addUseStatement($hostClass, $alias, $pathElements)
    {
        $hostClass->nameSpace->addUse(GeneratorPathBuilderService::buildUse($pathElements), $alias);
    }

    /**
     * @param $hostClass
     * @param $attributeName
     * @param $alias
     */
    protected static function addAttributeDeclaration($hostClass, $attributeName, $alias)
    {
        $hostClass->class->addProperty($attributeName)
            ->setVisibility('protected')
            ->setComment('@var ' . $alias . ' $' . $attributeName);
    }

    /**
     * @param $hostClass
     * @param $attributeName
     * @param $alias
     */
    protected static function addAttributeInstantiation($hostClass, $attributeName, $alias, $pathElements)
    {
        array_shift($pathElements); // remove "Wheel"
        array_shift($pathElements); // remove "Auto"
        array_shift($pathElements); // remove "Concept"
//        var_dump($pathElements);die();

        $hostClass->class->getMethod('__construct')
            ->addBody('$this->' . $attributeName . ' = PrototypeService::new(\'' . GeneratorPathBuilderService::buildFQName($pathElements) . '\');');
    }

    /**
     * @param $hostClass
     * @param $attributeName
     * @param $type
     * @param $pathElements
     * @param $bodyCallback
     */
    protected static function addAttributeSetter($hostClass, $attributeName, $type, $pathElements, $bodyCallback)
    {
        $attributeRubricClass = self::getAttributeRubricClass($pathElements, $attributeName);

        $setterMethod = $hostClass->class
            ->addMethod('set' . Util::pascalize($attributeName))
            ->setVisibility('public');

        $setterMethod
            ->addParameter($attributeName)
            // TODO 02: there won't be "reference" type
            ->setTypeHint($type === 'reference' ? $attributeRubricClass : $type)
            ->setNullable()
        ;

        $setterMethod->addComment('@param ' . ($type === 'reference' ? $attributeRubricClass : $type) . ' $' . $attributeName);
        $setterMethod->addComment('@return $this');

        $lines = $bodyCallback($attributeName);
        $setterMethod->addBody(implode("\n", $lines));
        $setterMethod->addBody('return $this;');
    }

    /**
     * @param $hostClass
     * @param $attributeName
     * @param $type
     * @param $pathElements
     * @param $bodyCallback
     */
    protected static function addAttributeGetter($hostClass, $attributeName, $type, $pathElements, $bodyCallback)
    {
        $attributeRubricClass = self::getAttributeRubricClass($pathElements, $attributeName);

        $lines = $bodyCallback($attributeName);

        $getterMethod = $hostClass->class->addMethod('get' . Util::pascalize($attributeName))
            ->setVisibility('public')
            ->setReturnType($type === 'reference' ? $attributeRubricClass : $type)
            // TODO 02: control this by a parameter
            ->setReturnNullable()
            ->addBody(implode("\n", $lines));

        $getterMethod->addComment('@return ' . ($type === 'reference' ? $attributeRubricClass : $type));

    }

    /**
     * @param $pathElements
     * @param $attributeName
     * @return array|string
     */
    public static function getAttributeRubricClass($pathElements, $attributeName)
    {
        // todo: Parameter should be a rubric, not an attribute here, work it out, current solution is a workaround

        $ext = array_pop($pathElements);
        array_pop($pathElements);
        array_pop($pathElements);
        array_push($pathElements, $attributeName);
        array_push($pathElements, $ext);

        return GeneratorPathBuilderService::buildFQName($pathElements);
    }

}