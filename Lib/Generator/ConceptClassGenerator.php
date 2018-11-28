<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 10:49
 */

namespace Lib\Generator;

use Lib\Util;
use Nette\PhpGenerator\PhpFile;

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
         * todo: Experimental code: build prototype class
         */
        $this->conceptPrototypeService = new PhpFile();
        $namespace = $this->conceptPrototypeService->addNamespace(GeneratorPathBuilderService::buildNameSpace($this->pathElements));
        $class = $namespace
            ->addClass('PrototypeService')
//            ->setExtends($this->parentClass)
//            ->addComment($this->getClassDocBlock())
        ;

        $class->setFinal();

        $prototypesArray = $class->addProperty('prototypes')->setStatic()->setVisibility('protected');

        $this->prototypeConstructor = $class->addMethod("__construct")->setVisibility('public');
        $this->prototypeConstructor->addBody('self::$prototypes = [];');

        $getter = $class->addMethod("get");
        $getter->setStatic();
        $getter->addParameter('className')->setTypeHint('string');
        $getter->addBody('return clone self::$prototypes[$className];');


        $this->prototypeConstructor->addBody('self::$prototypes["' . GeneratorPathBuilderService::buildFQName($this->pathElements) . '"] = new \\' . GeneratorPathBuilderService::buildFQName($this->pathElements) . '();');

        /**
         * todo: End of experimental code: build prototype class
         */


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

            /**
             * todo: Experimental code: add rubric to prototypes
             */
            $this->prototypeConstructor->addBody('self::$prototypes["' . GeneratorPathBuilderService::buildFQName($rubricPathElements) . '"] = new \\' . GeneratorPathBuilderService::buildFQName($rubricPathElements) . '();');

        }

        GeneratorService::addSourceFile($this->getSourceFileDescriptor());

        /**
         * todo: Experimental code: prototype class to sources
         */

        $protoPathElements = $this->pathElements;
        array_pop($protoPathElements);
        array_pop($protoPathElements);
        array_push($protoPathElements, 'PrototypeService');
        array_push($protoPathElements, 'php');

        GeneratorService::addSourceFile([
            'path' => GeneratorPathBuilderService::buildPath($protoPathElements),
            'content' => $this->conceptPrototypeService
        ]);

        var_dump([
            'path' => GeneratorPathBuilderService::buildPath($protoPathElements),
            'content' => $this->conceptPrototypeService
        ]);

        echo $this->conceptPrototypeService;
//        die();
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
                ClassVarGenerator::USE_STATEMENT,
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