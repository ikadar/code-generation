<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

use Kdyby\ParseUseStatements\UseStatements;
use Wheel\Concept\PrototypeService;

require __DIR__ . '/vendor/autoload.php';

PrototypeService::init();


$conceptName = 'Car';
$conceptFQName = 'Wheel\\Concept\\' . $conceptName;

$factory  = \phpDocumentor\Reflection\DocBlockFactory::createInstance();


$conceptDef = [];

$concept = new $conceptFQName();

$conceptClassReflection = new ReflectionClass($concept);
$conceptName = \Lib\Util::snakeize($conceptClassReflection->getShortName());
echo ("Concept class FQName: " . $conceptClassReflection->getName());
$conceptDef["name"] = $conceptName;
$conceptDef["rubrics"] = [];
echo("\n");
echo ("Concept class short name: " . $conceptName);
echo("\n");
echo("\n");

$conceptUses = UseStatements::getUseStatements($conceptClassReflection);
//var_dump($conceptClassReflection->getNamespaceName());
//var_dump($conceptUses);

$conceptClassFileSource = file_get_contents($conceptClassReflection->getFileName());
//echo $conceptClassFileSource;

$conceptDocblock = $factory->create($conceptClassFileSource);


$conceptVarTags = $conceptDocblock->getTagsByName('var');
foreach ($conceptVarTags as $conceptVarTag) {

    $rubricDef = [];

    $rubricName = $conceptVarTag->getType()->getFqSen()->getName();
    $rubricFQName = $conceptUses[$rubricName];
    $rubricClassReflection = new ReflectionClass($rubricFQName);

    /**
     * @var array $rubricUses Associative array
     * in which keys are class aliases, values are corresponding FQSEN strings
     */
    $rubricUses = UseStatements::getUseStatements($rubricClassReflection);


    $rubricFqsen = (string)$conceptVarTag->getType()->getFqsen();
    $rubricShortName = $conceptVarTag->getVariableName();
    echo ("Concept's rubric variable short name: " . $rubricShortName);
    $rubricDef["name"] = $rubricShortName;
    $rubricDef['attributes'] = [];
    echo("\n");
    echo ("Rubric class fq name: " . $rubricFqsen);
    echo("\n");
    echo("\n");

    $rubricClassFileSource = file_get_contents($rubricClassReflection->getFileName());
    $rubricDocblock = $factory->create($rubricClassFileSource);
    $rubricVarTags = $rubricDocblock->getTagsByName('var');

    foreach ($rubricVarTags as $rubricVarTag) {
        /**
         * Get rubric attribute FQ name.
         *
         * Extracts attribute class alias from its docblock,
         * finds the FQ name of the class in $rubricUses array @see $rubricUses
         * and creates reflection object for Attribute class.
         */
//        $attributeName = $rubricVarTag->getType()->getFqsen()->getName();
//        $attributeFQName = $rubricUses[$attributeName];
//        $attributeClassReflection = new ReflectionClass($attributeFQName);

        $attributeClassReflection = getReflectionClass($rubricVarTag, $rubricUses);

        $rubricDef['attributes'][] = parseAttr($attributeClassReflection);
    }
    $conceptDef['rubrics'][] = $rubricDef;
}

echo("\n");
echo("\n");
echo(json_encode($conceptDef, JSON_PRETTY_PRINT));

/**
 * Crates reflection class for attribute type class variables from "@var" docblock reflection object.
 * Finds the FQ name of the class in $rubricUses array @see $rubricUses
 *
 * @param $rubricVarTag
 * @param $rubricUses
 * @return ReflectionClass
 * @throws ReflectionException
 */
function getReflectionClass($rubricVarTag, $rubricUses)
{
    /**
     * Get rubric attribute FQ name.
     *
     * Extracts attribute class alias from its docblock reflection,
     * finds the FQ name of the class in $rubricUses array @see $rubricUses
     * and creates reflection object for Attribute class.
     */
    $attributeName = $rubricVarTag->getType()->getFqsen()->getName();
    $attributeFQName = $rubricUses[$attributeName];
    return new ReflectionClass($attributeFQName);

}

/**
 *
 * Creates attribute class definition array from
 * Uses 1. class reflection, 2. instance, 3, docblock reflection
 *
 * @param $attributeClassReflection
 * @return array
 */
function parseAttr($attributeClassReflection)
{
    /*
     * Initializes attribute definition with attribute name
     */
    $attributeDef = [
        'name' => $attributeClassReflection->getShortName()
    ];

    /*
     * Instantiate attribute class
     */
    $attributeInstance = $attributeClassReflection->newInstance();

    /*
     * Create docblock reflection
     */
    $attributeDocBlockReflection = getDocBlockReflection($attributeClassReflection);

    /*
     * Loop over @var tags in attribute source,
     * add instance's property values to class definition
     */
    $attributeVarTags = $attributeDocBlockReflection->getTagsByName('var');
    foreach ($attributeVarTags as $attributeVarTag) {
        /*
         * add key-value pairs to attribute descriptor
         * E.g:
         * "name": "plate_number",
         * "type": "string",
         */
        $propertyName = $attributeVarTag->getVariableName();
        $propertyValue = $attributeInstance::$$propertyName;
        $attributeDef[$propertyName] = $propertyValue;
    }

    return $attributeDef;
}

function getDocBlockReflection($classReflection)
{
    $factory  = \phpDocumentor\Reflection\DocBlockFactory::createInstance();
    $attributeClassFileSource = file_get_contents($classReflection->getFileName());
    return $factory->create($attributeClassFileSource);
}