<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

//passthru('ls -la Wheel/Concept', $result);
//passthru('rm -rf Wheel/Concept', $result);
passthru('rm -rf Wheel/Auto', $result);

//var_dump($result);

//die();

require __DIR__ . '/config/bootstrap.php';

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
PrototypeClassGenerator::initialize();

// Generate concept
$generator = new \Lib\Generator\GeneratorService();
$generator->generateConceptSource($conceptJsonFile);

SourcePool::addSourceFile([
    'path' => PrototypeClassGenerator::getPath(),
    'content' => PrototypeClassGenerator::getSource()
]);

SourcePool::dump();

SourcePool::debug();

