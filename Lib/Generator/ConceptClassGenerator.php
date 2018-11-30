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
 * Class ConceptClassGenerator
 * @package Lib
 */
class ConceptClassGenerator extends ClassGenerator
{

    public $conceptPrototypeService;
    public $prototypeConstructor;

    /**
     * ConceptClassGenerator constructor.
     * @param $pathElements
     */
    public function __construct($pathElements)
    {
        $this->parentClass = 'Wheel\Core\Concept';
        parent::__construct($pathElements);

        $this->nameSpace->addUse('Wheel\\Concept\\PrototypeService');
    }

    /**
     *
     * Generates Concept class source file path and content. Adds it to GeneratorService source file collection
     *
     * @param $concept
     * @return $this
     */
    function generateConceptClass($concept)
    {

        /**
         * Add concept class to prototype service
         */
        PrototypeClassGenerator::addClass(GeneratorPathBuilderService::buildFQName($this->pathElements));

        /**
         * Generate and add concept's rubrics
         */
        foreach ($concept['rubrics'] as $rubricConfiguration) {

            /**
             * Calculate rubric path elements
             */
            $rubricPathElements = GeneratorPathBuilderService::getConceptRootRelativePathElements([
                $concept['name'],
                $rubricConfiguration['name'],
                'php'
            ]);

            /**
             * Generate rubric class source code
             */
            $rubricClassGenerator = new RubricClassGenerator($rubricPathElements);
            $rubricClassGenerator->generateRubricClass($rubricConfiguration, $this);

            /**
             * Add rubric to concept class source
             */
            $rubricConfiguration['type'] = GeneratorPathBuilderService::buildFQName($rubricPathElements);
            $this->addRubric($rubricConfiguration, $rubricPathElements);

        }

        /**
         * Add concept class source to source pool
         */
        SourcePool::addSourceFile($this->getSourceFileDescriptor());

        return $this;
    }

    /**
     * @param $rubricConfiguration
     * @param $rubricPathElements
     */
    private function addRubric($rubricConfiguration, $rubricPathElements)
    {
        $rubricAlias = RubricClassGenerator::getRubricAlias($rubricPathElements);

        ClassVarGenerator::addClassVar(
            $this,
            $rubricConfiguration['name'],
            $rubricConfiguration['type'],
            $rubricAlias,
            $rubricPathElements,
            [
//                ClassVarGenerator::USE_STATEMENT,
                ClassVarGenerator::DECLARATION,
                ClassVarGenerator::INSTANTIATION,
                ClassVarGenerator::SETTER,
                ClassVarGenerator::GETTER
            ]
        );
    }

    /**
     * @return string
     */
    protected function getClassDocBlock()
    {
        return $this->buildWheelDottedPath() . ' concept class.';
    }

    /**
     *
     * Return pascalised concept name
     *
     * @return string
     */
    protected function buildWheelDottedPath()
    {
        $pathElements = $this->pathElements;
        array_pop($pathElements); // extension
        $conceptName = Util::pascalize(array_pop($pathElements));
        return Util::pascalize(implode('.', [$conceptName]));
    }

}