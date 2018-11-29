<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/vendor/autoload.php';

use Lib\Generator\PrototypeClassGenerator;
use Lib\Generator\SourcePool;

// Read command line param
if (!array_key_exists(1, $argv)) {
    $conceptJsonFile = 'concept.json';
} else {
    $conceptJsonFile = $argv[1];
}

/**
 * Initialize prototype class
 */
PrototypeClassGenerator::initialize(['Wheel', 'Concept']);

// Generate concept
$generator = new \Lib\Generator\GeneratorService();
$generator->generateConceptSource($conceptJsonFile);

SourcePool::addSourceFile([
    'path' => PrototypeClassGenerator::getPath(),
    'content' => PrototypeClassGenerator::getSource()
]);

SourcePool::dump();

