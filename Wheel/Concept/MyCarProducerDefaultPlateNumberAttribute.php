<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 12. 14.
 * Time: 18:10
 */

namespace Wheel\Concept;


use Wheel\Auto\Concept\Car\Producer\DefaultPlateNumberAttribute;

class MyCarProducerDefaultPlateNumberAttribute extends DefaultPlateNumberAttribute
{
    /**
     * @param null $value
     */
    public function setValue($value)
    {
        parent::setValue(strtoupper($value));
    }

}