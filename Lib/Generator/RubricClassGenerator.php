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
 * Class RubricClassGenerator
 * @package Lib\Generator
 */
class RubricClassGenerator extends ClassGenerator
{
    protected $proxyGenerator;

    /**
     * RubricClassGenerator constructor.
     * @param $pathElements
     */
    public function __construct($pathElements)
    {
        $this->parentClass = Util::addTrailingCoreNS('Rubric');
        parent::__construct($pathElements);

        $this->nameSpace->addUse(Util::addTrailingAutoGenConceptNS('PrototypeService'));

        $this->proxyGenerator = new RubricProxyClassGenerator($pathElements);
    }

    /**
     * Generates Rubric class source file path and content. Adds it to GeneratorService source file collection
     *
     * @param $rubricConfiguration
     * @param $conceptClass
     * @return $this
     */
    function generateRubricClass($rubricConfiguration, $conceptClass)
    {
        /**
         * Add rubric to prototype class
         */
        PrototypeClassGenerator::addClass(GeneratorPathBuilderService::buildFQName($this->pathElements));

        /**
         * Generate and add rubric's attributes
         */
        foreach ($rubricConfiguration['attributes'] as $attributeConfiguration) {

            /**
             * Calculate attribute path elements
             */
            $attributePathElements = GeneratorPathBuilderService::getConceptRootRelativePathElements([
                $conceptClass->className,
                $rubricConfiguration['name'],
                $attributeConfiguration['name'] . 'Attribute',
                'php'
            ]);

            /**
             * Generate attribute class source code
             */
            $attributeClassGenerator = new AttributeClassGenerator($attributePathElements);
            $attributeClassGenerator->generateAttributeClass($attributeConfiguration);

            /**
             * Add attribute to rubric class source
             */
            $this->addAttribute($attributeConfiguration, $attributePathElements);

            /**
             * Add attribute to rubric proxy class source
             */
            $this->proxyGenerator->addAttribute($attributeConfiguration, $attributePathElements);

        }

        /**
         * Add rubric source to pool
         */
        SourcePool::addSourceFile($this->getSourceFileDescriptor());

        /**
         * Add rubric proxy source to pool
         */
        SourcePool::addSourceFile($this->proxyGenerator->getSourceFileDescriptor());

        return $this;
    }

    /**
     * @param $attributeConfiguration
     * @param $attributePathElements
     */
    private function addAttribute($attributeConfiguration, $attributePathElements)
    {
        $type = $attributeConfiguration['type'];

        if ($type === 'reference') {
            $type = ClassGenerator::createInterfaceForReferenceTypes($attributeConfiguration['referredRubrics']);
        }

        ClassVarGenerator::addClassVar(
            $this,
            $attributeConfiguration['name'],
            $type,
            null,
            $attributePathElements,
            [
                ClassVarGenerator::USE_STATEMENT,
                ClassVarGenerator::DECLARATION,
                ClassVarGenerator::INSTANTIATION,
                ClassVarGenerator::SETTER,
                ClassVarGenerator::GETTER
            ],
            function ($attributeName) {
                return [
                    '$this->' . $attributeName . '->setValue($' . $attributeName . ');'
                ];
            },
            function ($attributeName) use ($attributeConfiguration) {

                $lines = [];

                $lines[] = '$cascadingSources = [';
                if (array_key_exists('cascadingSources', $attributeConfiguration)) {
                    foreach ($attributeConfiguration['cascadingSources'] as $cascadingSourcePath) {
                        $pathElements = [];
                        foreach ($cascadingSourcePath as $cascadingSource) {
                            $pathElements[] = '\'' . $cascadingSource . '\'';
                        }
                        $lines[] = "\t" . '[' . implode(', ', $pathElements) . '],';
                    }
                }
                $lines[] = '];';

                $lines[] = 'return $this->' . $attributeName . '->getValue() ?: $this->cascade($cascadingSources);';

                return $lines;
            }
        );
    }

    /**
     * @param $pathElements
     * @return string
     */
    public static function getRubricAlias($pathElements)
    {
        array_pop($pathElements); // extension
        $rubricName = array_pop($pathElements);
        $conceptName = array_pop($pathElements);
        return Util::pascalize(implode('_', [$conceptName, $rubricName]));
    }

    /**
     * @return string
     */
    protected function getClassDocBlock()
    {
        return $this->buildWheelDottedPath() . ' rubric class.';
    }

    /**
     *
     * Builds a {concept}.{rubric} style string. E.g: Car.Owner
     *
     * @return string
     */
    protected function buildWheelDottedPath()
    {
        $pathElements = $this->pathElements;
        array_pop($pathElements); // extension
        $rubricName = Util::pascalize(array_pop($pathElements));
        $conceptName = Util::pascalize(array_pop($pathElements));
        return Util::pascalize(implode('.', [$conceptName, $rubricName]));
    }

}