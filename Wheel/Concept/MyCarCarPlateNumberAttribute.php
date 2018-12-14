<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 12. 14.
 * Time: 18:10
 */

namespace Wheel\Concept;


use Wheel\Auto\Concept\Car\Car\PlateNumberAttribute;

class MyCarCarPlateNumberAttribute extends PlateNumberAttribute
{
    /**
     * @param null $value
     */
    public function setValue($value)
    {
        parent::setValue(strtoupper($value));
    }

}