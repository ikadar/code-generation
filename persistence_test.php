<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/vendor/autoload.php';

$carProto = new \Wheel\Concept\CarPrototypeService();

$carConcept = \Wheel\Concept\CarPrototypeService::get('Wheel\Concept\Car');
$carColorRubric = \Wheel\Concept\CarPrototypeService::get('Wheel\Concept\Car\Color');

var_dump($carConcept);
var_dump($carColorRubric);
