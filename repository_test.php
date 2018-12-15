<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/bootstrap.php';

use Wheel\Core\Repository\BaseRepository;
use Wheel\Core\Persistence\InMemoryStorage;
use Wheel\Core\PrototypeService;

$storage = new InMemoryStorage();

$repository = new BaseRepository($storage);

$cars = [
    [
        '__type' => 'Car.Car',
        'plate_number' => 'ag-27-44',
        'color' => [
            '__type' => 'Car.Color',
            'name' => 'Green',
            'code' => 'green',
        ],
        'brand' => [
            '__type' => 'Car.Brand',
            'name' => 'Wartburg',
            'code' => 'wartburg',
        ]
    ],
    [
        '__type' => 'Car.Car',
        'plate_number' => 'tz-41-72',
        'color' => [
            '__type' => 'Car.Color',
            'name' => 'Red',
            'code' => 'red',
        ],
        'brand' => [
            '__type' => 'Car.Brand',
            'name' => 'Trabant',
            'code' => 'trabant',
        ]
    ],
    [
        '__type' => 'Car.Car',
//        'plate_number' => 'gh-33-17',
        'color' => [
//            '__type' => 'Car.Brand',
            '__type' => 'Car.Color',
            'name' => 'Blue',
            'code' => 'blue',
        ],
        'brand' => [
            '__type' => 'Car.Brand',
            'name' => 'Lada',
            'code' => 'lada',
            'producer' => [
                '__type' => 'Car.Producer',
                'name' => 'Lada Factory',
                'code' => 'lada_factory',
                'default_plate_number' => 'lada-fac-001'
            ],
            'default_plate_number' => 'lada-001'
        ],
        'owner' => [
            '__type' => 'Car.Person',
            'name' => 'John Doe',
            'age' => 30,
            'gender' => [
                '__type' => 'Car.Gender',
                'name' => 'Male',
                'code' => 'male'
            ]
        ]
    ]
];


foreach ($cars as $carData) {
    $car = PrototypeService::new('\Car\CarProxy');
    $car->load($carData);
    $repository->add($car);
}
//$repository->dump();

$t0 = microtime(true);
$list = $repository->list();
$t1 = microtime(true);
//var_dump($list);

$entity = end($list);
//var_dump($entity);
//var_dump($entity->get());

$entity = $repository->getById($entity->id);
//var_dump($entity);
var_dump($entity->getPlateNumber());
var_dump($entity->getColor()->getName());
var_dump($entity->getBrand()->getName());
var_dump($entity->getOwner()->getName());
var_dump($entity->getOwner()->getAge());
var_dump($entity->getOwner()->getGender()->getName());


$yellowColor = PrototypeService::new('\Car\ColorProxy');
$yellowColor->load([
    "name" => "Yellow",
    "code" => "yellow"
]);

$entity->setColor($yellowColor->get());
var_dump($entity->getColor()->getName());

$repository->edit($entity);

$repository->delete($entity->id);
$list = $repository->list();
//print_r($list);
//var_dump($list);

//$entity = $list[0];

//$presenter = new \Wheel\Core\Presentation\RubricEditionPresenter($entity);
//var_dump($presenter);

//echo($presenter->getData());

var_dump(round($t1-$t0, 4));