<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/vendor/autoload.php';


use Wheel\Concept\PrototypeService;
use Wheel\Concept\Car;


PrototypeService::init();

$t0 = microtime(true);

$carConcept = new Car();


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
