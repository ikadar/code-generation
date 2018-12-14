<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 12. 14.
 * Time: 18:06
 */

namespace Wheel\Concept;

use Wheel\Auto\Concept\Car\CarProxy;

class MyCarRubric extends CarProxy
{
    /**
     * @param array $data
     * @throws \Exception
     */
    public function load(array $data)
    {
        parent::load($data);
//        var_dump('CAR PROXY LOAD VOLT');
    }

}