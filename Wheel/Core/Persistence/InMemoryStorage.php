<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 30.
 * Time: 14:09
 */

namespace Wheel\Core\Persistence;


/**
 * Class InMemoryStorage
 * @package Wheel\Core\Persistence
 */
class InMemoryStorage Implements StorageInterface
{
    protected static $storage;

    public function __construct()
    {
        self::$storage = [];
    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function getById($id): array
    {
        if (array_key_exists($id, self::$storage)) {
            return self::$storage[$id];
        } else {
            throw new \Exception('ID not found: ' . $id);
        }
    }

    /**
     * @param callable|null $callable
     * @return array
     */
    public function list(callable $callable = null): array
    {
        if ($callable !== null) {
            $list = array_filter(self::$storage, $callable);
        } else {
            $list = self::$storage;
        }

        return $list;
    }

    /**
     * @param $entity
     * @return array
     */
    public function add(array $entity): array
    {
        $id = self::newId();
        $entity['@id'] = $id;
        self::$storage[$id] = $entity;
        return $entity;
    }

    /**
     * @param array $entity
     * @return array
     * @throws \Exception
     */
    public function edit(array $entity): array
    {
        // call just because it throws exception in case of id doesn't exist
        $this->getById($entity['@id']);
        $persistentEntity = $entity;
        self::$storage[$persistentEntity['@id']] = $persistentEntity;
        return $persistentEntity;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function delete($id): void
    {
        // call just because it throws exception in case of id doesn't exist
        $this->getById($id);
        unset(self::$storage[$id]);
    }

    protected static function newId()
    {
        return uniqid();
    }

    public static function dump()
    {
        var_dump(self::$storage);
    }

}