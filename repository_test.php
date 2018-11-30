<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

require __DIR__ . '/vendor/autoload.php';

use Wheel\Core\Persistence\InMemoryStorage;

$storage = new InMemoryStorage();
InMemoryStorage::dump();


$entities = [
    [
        "a" => "b",
        "b" => 1,
        "c" => true,
    ],
    [
        "d" => "e",
        "e" => -99,
        "c" => [1, "qwe"],
    ]
];

foreach ($entities as $entity) {
    $entity = $storage->add($entity);
}
InMemoryStorage::dump();


$list = $storage->list();
//var_dump($list);

$entity = end($list);
//var_dump($entity);

$entity['d'] = 'aaa';
$storage->edit($entity);

InMemoryStorage::dump();

$storage->delete($entity['id']);
InMemoryStorage::dump();

