<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

class RubricProxy
{
    protected $section = null;

    public function __construct()
    {
    }

    public function get()
    {
        return $this->section;
    }
}