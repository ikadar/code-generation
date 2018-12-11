<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/vendor/autoload.php';

use Wheel\Concept\PrototypeService;
use Wheel\Core\Repository\BaseRepository;
use Wheel\Core\Persistence\InMemoryStorage;

PrototypeService::init();

$storage = new InMemoryStorage();

$repository = new BaseRepository($storage);

$cars = [
    [
        'plate_number' => 'AG-27-44',
        'color' => [
            'name' => 'Green',
            'code' => 'green',
        ],
        'brand' => [
            'name' => 'Wartburg',
            'code' => 'wartburg',
        ]
    ],
    [
        'plate_number' => 'TZ-41-72',
        'color' => [
            'name' => 'Red',
            'code' => 'red',
        ],
        'brand' => [
            'name' => 'Trabant',
            'code' => 'trabant',
        ]
    ],
    [
        'plate_number' => 'GH-33-17',
        'color' => [
            'name' => 'Blue',
            'code' => 'blue',
        ],
        'brand' => [
            'name' => 'Lada',
            'code' => 'lada',
            'producer' => [
                'name' => 'Lada Factory',
                'code' => 'lada_factory',
                'default_plate_number' => 'LADA-FAC-001'
            ],
//            'default_plate_number' => 'LADA-001'
        ],
    ]
];


foreach ($cars as $carData) {
    $car = PrototypeService::new('\Wheel\Concept\Car\CarProxy');
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


$yellowColor = PrototypeService::new('\Wheel\Concept\Car\ColorProxy');
$yellowColor->load([
    "name" => "Yellow",
    "code" => "yellow"
]);

$entity->setColor($yellowColor->get());
var_dump($entity->getColor()->getName());

$repository->edit($entity);

$repository->delete($entity->id);
$list = $repository->list();
print_r($list);
//var_dump($list);

//$entity = $list[0];

//$presenter = new \Wheel\Core\Presentation\RubricEditionPresenter($entity);
//var_dump($presenter);

//echo($presenter->getData());

//var_dump(round($t1-$t0, 4));