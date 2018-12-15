<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 29.
 * Time: 7:53
 */

namespace Lib\Generator;

use Lib\Util;
/**
 * Class PrototypeClassGenerator
 *
 * todo: this class should extend ClassGenerator class, but currently there are conflicts between static and non-static variables of these classes
 *
 * @package Lib\Generator
 */

class RubricProxyClassGenerator extends ClassGenerator
{

    /**
     * RubricProxyClassGenerator constructor.
     * @param $pathElements
     */
    public function __construct($proxiedClassPathElements)
    {
        $this->parentClass = Util::addTrailingCoreNS('RubricProxy');
        $this->pathElements = $this->buildProxyClassPathElements($proxiedClassPathElements);
        parent::__construct($this->pathElements);

//        $this->nameSpace->addUse(Util::addTrailingAutoGenConceptNS('PrototypeService'));

        // TODO 02: do it well
        array_shift($proxiedClassPathElements); // remove "Wheel"
        array_shift($proxiedClassPathElements); // remove "Auto"
        array_shift($proxiedClassPathElements); // remove "Concept"

        $this->constructor->addBody('$this->section = PrototypeService::new(\'' . GeneratorPathBuilderService::buildFQName($proxiedClassPathElements) . '\');');

        PrototypeClassGenerator::addClass(GeneratorPathBuilderService::buildFQName($this->pathElements));
    }

    /**
     * @return mixed
     */
    protected function getClassDocBlock(){
        return '';
    }

    /**
     * @return mixed
     */
    protected function buildWheelDottedPath(){}

    protected function buildProxyClassPathElements($pathElements)
    {
        $proxyPathElements = $pathElements;
        $extension = array_pop($proxyPathElements);
        $className = array_pop($proxyPathElements);
        $className .= 'Proxy';
        array_push($proxyPathElements, $className);
        array_push($proxyPathElements, $extension);

        return $proxyPathElements;
    }

    /**
     * @param $attributeConfiguration
     * @param $attributePathElements
     */
    public function addAttribute($attributeConfiguration, $attributePathElements)
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
//                ClassVarGenerator::USE_STATEMENT,
//                ClassVarGenerator::DECLARATION,
//                ClassVarGenerator::INSTANTIATION,
                ClassVarGenerator::SETTER,
                ClassVarGenerator::GETTER
            ],
            function ($attributeName) {
                return [
                    '$this->section->set' . Util::pascalize($attributeName) . '($' . $attributeName . ');'
                ];
            },
            function ($attributeName) {
                return [
                    'return $this->section->get' . Util::pascalize($attributeName) . '();'
                ];
            }
        );
    }

}