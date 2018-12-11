<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

class Concept
{
    /**
     * Clone
     */
    public function __clone()
    {
        $this->__construct();
    }

}