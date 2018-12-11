<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

use Wheel\Concept\PrototypeService;

class RubricProxy
{

    /**
     * @var Rubric null
     */
    protected $section = null;

    public $id;

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

    public function get()
    {
        return $this->section;
    }

    public function getData()
    {
        return $this->section->getData();
    }

    public function getRubricClass()
    {
        return get_class($this->section);
    }

    public function load(array $data)
    {
        $this->section->load($data);
    }

}