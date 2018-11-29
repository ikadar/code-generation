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
 * Class AttributeClassGenerator
 *
 * Generates attribute class source code
 *
 * @package Lib\Generator
 */
class AttributeClassGenerator extends ClassGenerator
{

    /**
     * AttributeClassGenerator constructor.
     * @param $pathElements array containing elements of the class file path. [{folder}, {folder}, ... {file}, {extension}]
     */
    public function __construct($pathElements)
    {
        $this->parentClass = 'Wheel\Core\Attribute';
        parent::__construct($pathElements);
    }

    /**
     *
     * Generates Attribute class source file path and content. Adds it to GeneratorService source file collection
     *
     * @param $attributeConfiguration
     * @return $this
     */
    function generateAttributeClass($attributeConfiguration)
    {

        /**
         * Add attribute to prototype class
         */
        PrototypeClassGenerator::addClass(GeneratorPathBuilderService::buildFQName($this->pathElements));

        foreach ($attributeConfiguration as $configurationKey => $configurationValue) {

            /**
             * Add attribute properties declarations and their values from configuration
             */
            $this->class->addProperty($configurationKey)
                // todo: they should be protected instead, however php default reflection can't see them. Try https://github.com/Roave/BetterReflection
                ->setVisibility('public')
                ->setStatic()
                // todo: add type
                ->setComment('@var $' . $configurationKey);

            /**
             * Set attribute properties values in constructor
             */
            $this->constructor->addBody('self::$' . $configurationKey . ' = ?;', [$configurationValue]);
        }

        /**
         * Add attribute class source to source pool
         */
        SourcePool::addSourceFile($this->getSourceFileDescriptor());

        return $this;
    }

    /**
     * @return string
     */
    protected function getClassDocBlock()
    {
        return $this->buildWheelDottedPath() . ' attribute class.';
    }

    /**
     *
     * Builds a {concept}.{rubric}.{attribute} style string. E.g: Car.Owner.Name
     *
     * @return string
     */
    protected function buildWheelDottedPath()
    {
        $pathElements = $this->pathElements;
        array_pop($pathElements); // extension
        $attributeName = Util::pascalize(array_pop($pathElements));
        $rubricName = Util::pascalize(array_pop($pathElements));
        $conceptName = Util::pascalize(array_pop($pathElements));

        return Util::pascalize(implode('.', [$conceptName, $rubricName, $attributeName]));
    }

    /**
     * @param $pathElements
     * @return string
     */
    static public function getAttributeAlias($pathElements)
    {
        array_pop($pathElements); // extension
        $attributeName = array_pop($pathElements);
        return Util::pascalize($attributeName);
    }

}