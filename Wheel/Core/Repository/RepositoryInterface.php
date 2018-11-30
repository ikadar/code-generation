<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 30.
 * Time: 14:11
 */

namespace Wheel\Core\Repository;

use Wheel\Core\RubricProxy;

interface RepositoryInterface {

    public function getById($id): RubricProxy;

    public function list(callable $callable = null): array;

    public function add(RubricProxy $entity): RubricProxy;

    public function edit(RubricProxy $entity): RubricProxy;

    public function delete($id): void;

}