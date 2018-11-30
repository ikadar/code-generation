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
    protected $section = null;

    public function __construct($className)
    {
        $this->section = PrototypeService::new($className);
    }

    public function get()
    {
        return $this->section;
    }
}