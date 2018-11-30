<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 30.
 * Time: 14:11
 */

namespace Wheel\Core\Persistence;

interface StorageInterface {

    public function getById($id): array;

    public function list(callable $callable = null): array;

    public function add(array $entity): array;

    public function edit(array $entity): array;

    public function delete($id): void;

}