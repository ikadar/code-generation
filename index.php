<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/vendor/autoload.php';

//$carConcept = new \Wheel\Concept\Car();
//
//
//print_r(get_declared_classes()); die();
//
//$wheelCustomClasses = array_values(array_filter(get_declared_classes(), function ($item) {
//    return strpos($item, 'Wheel\\Concept\\') === 0;
//}));
//
//
//print_r($wheelCustomClasses); die();
//
//$proto = [];
//foreach ($wheelCustomClasses as $wheelCustomClass) {
////    $object = new $wheelCustomClass();
//    $proto[$wheelCustomClass] = new $wheelCustomClass();
//}
//
//print_r($proto);
//
////$cc = clone $proto['Wheel\\Concept\\Car'];
//
////print_r($cc);
////var_dump($carConcept);
//die();

$carConcept = new \Wheel\Concept\Car();

$t0 = microtime(true);

$oneCar = $carConcept->getCar();

$newColor = $carConcept->getColor()
    ->setName('Red')
    ->setcode('red')
;

$oneCar->load([
    'plate_number' => 'TZ-41-72',
    'color' => $newColor,
    'brand' => [
        'name' => 'Trabant',
        'code' => 'trabant',
    ]
]);

var_dump($oneCar);
var_dump($oneCar->getPlateNumber());
var_dump($oneCar->getColor()->getName() . ' (' . $oneCar->getColor()->getCode() . ')');
var_dump($oneCar->getBrand()->getName() . ' (' . $oneCar->getBrand()->getCode() . ')');

$anotherCar = $carConcept->getCar();

$anotherCar->load([
    'plate_number' => 'GH-33-17',
    'color' => [
        'name' => 'Blue',
        'code' => 'blue',
    ],
    'brand' => [
        'name' => 'Lada',
        'code' => 'lada',
    ]
]);

var_dump($anotherCar);
var_dump($anotherCar->getPlateNumber());
var_dump($anotherCar->getColor()->getName() . ' (' . $anotherCar->getColor()->getCode() . ')');
var_dump($anotherCar->getBrand()->getName() . ' (' . $anotherCar->getBrand()->getCode() . ')');

$t1 = microtime(true);
$d = round($t1-$t0, 4);
var_dump($d);
