<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 30.
 * Time: 15:04
 */

namespace Wheel\Core\Repository;

use Wheel\Concept\PrototypeService;
use Wheel\Core\Persistence\StorageInterface;
use Wheel\Core\RubricProxy;

/**
 * Class BaseRepository
 * @package Wheel\Core\Repository
 */
class BaseRepository implements RepositoryInterface
{
    /**
     * @var
     */
    protected $storage;

    /**
     * BaseRepository constructor.
     * @param $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $id
     * @return RubricProxy
     * @throws \Exception
     */
    public function getById($id): RubricProxy
    {
        $data = $this->storage->getById($id);
        // Todo: implement a better way that allows rubric class to get its proxy class
        $entity = PrototypeService::new($data['@class'] . 'Proxy');
        $entity->load($data);
        $entity->id = $id;
        return $entity;

    }

    /**
     * @param callable|null $callable
     * @return array
     * @throws \Exception
     */
    public function list(callable $callable = null): array
    {
        if ($callable !== null) {
            $list = array_filter($this->storage->list(), $callable);
        } else {
            $list = $this->storage->list();
        }

        /**
         * Instantiate business objects
         */
        foreach ($list as $id => $data) {
            // Todo: implement a better way that allows rubric class to get its proxy class
            $entity = PrototypeService::new($data['@class'] . 'Proxy');
            $entity->load($data);
            $entity->id = $id;
            unset($list[$id]);
            $list[] = $entity;
        }

        return $list;
    }

    /**
     * @param RubricProxy $entity
     * @return RubricProxy
     */
    public function add(RubricProxy $entity): RubricProxy
    {
        $entityData = $entity->getData();
        $entityData['@class'] = '\\' . $entity->getRubricClass();
        $entityData = $this->storage->add($entityData);
        $entity->load($entityData);
        return $entity;
    }

    /**
     * @param RubricProxy $entity
     * @return RubricProxy
     */
    public function edit(RubricProxy $entity): RubricProxy
    {
        $entityData = $entity->getData();
        $entityData['@id'] = $entity->id;
        $entityData['@class'] = '\\' . $entity->getRubricClass();
        $entityData = $this->storage->edit($entityData);
        $entity->load($entityData);

        return $entity;
    }

    /**
     * @param $id
     */
    public function delete($id): void
    {
        $this->storage->delete($id);
    }

    public function dump()
    {
        $this->storage->dump();
    }

}