<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/vendor/autoload.php';

// Read command line param
if (!array_key_exists(1, $argv)) {
    $conceptJsonFile = 'concept.json';
} else {
    $conceptJsonFile = $argv[1];
}

// Generate concept
$generator = new \Lib\Generator\GeneratorService();
$generator->generateConceptSource($conceptJsonFile);
\Lib\Generator\SourcePool::dump();

