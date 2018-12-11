<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/vendor/autoload.php';

use Wheel\Concept\PrototypeService;

include("repository.php");

PrototypeService::init();


$t0 = microtime(true);

$oneCar = PrototypeService::new('\Wheel\Concept\Car\CarProxy');

//$newColor = PrototypeService::new('\Wheel\Concept\Car\ColorProxy')
//    ->setName('Red')
//    ->setcode('red')
//;

$oneCar->load($repository->get('car.trabant'));

var_dump($oneCar);
var_dump($oneCar->getPlateNumber());
var_dump($oneCar->getColor()->getName() . ' (' . $oneCar->getColor()->getCode() . ')');
var_dump($oneCar->getBrand()->getName() . ' (' . $oneCar->getBrand()->getCode() . ')');

$anotherCar = PrototypeService::new('\Wheel\Concept\Car\CarProxy');
//$anotherCar = new \Wheel\Concept\Car\CarProxy();

//$anotherCar->load([
//    'plate_number' => 'GH-33-17',
//    'color' => [
//        'name' => 'Blue',
//        'code' => 'blue',
//    ],
//    'brand' => [
//        'name' => 'Lada',
//        'code' => 'lada',
//    ]
//]);
$anotherCar->load($repository->get('car.lada'));

var_dump($anotherCar);
var_dump($anotherCar->getPlateNumber());
var_dump($anotherCar->getColor()->getName() . ' (' . $anotherCar->getColor()->getCode() . ')');
var_dump($anotherCar->getBrand()->getName() . ' (' . $anotherCar->getBrand()->getCode() . ')');



$t1 = microtime(true);
$d = round($t1-$t0, 4);
var_dump($d);
