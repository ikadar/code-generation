<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

use Wheel\Concept\PrototypeService;

/**
 * Class RubricProxy
 *
 * @package Wheel\Core
 */
class RubricProxy
{

    /**
     * @var Rubric null
     */
    protected $section = null;

    /**
     * @var
     */
    public $id;

    /**
     * RubricProxy constructor.
     *
     * @param  $className
     * @throws \Exception
     */
    public function __construct($className)
    {
        $this->section = PrototypeService::new($className);
    }

    /**
     * Clone
     */
    public function __clone()
    {
        $this->__construct();
    }

    /**
     * @return Rubric
     */
    public function get()
    {
        return $this->section;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->section->getData();
    }

    /**
     * @return string
     */
    public function getRubricClass()
    {
        return get_class($this->section);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function load(array $data)
    {
        $this->section->load($data);
    }

    public function validate()
    {
        return $this->section->validate();
    }

}