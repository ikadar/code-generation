<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 12. 14.
 * Time: 17:41
 */

// TODO 05: Make prototypes to implement its own interface, so if 3rd party overwrites it, it should implement it too

namespace Wheel\Core;

use Wheel\Auto\PrototypeService as BasePrototypeService;


class PrototypeService extends BasePrototypeService
{

    public static $assignments = [
        '\Car\CarProxy' => '\Wheel\Concept\MyCarRubric',
        '\Car\Car\PlateNumberAttribute' => '\Wheel\Concept\MyCarCarPlateNumberAttribute',
        '\Car\Brand\DefaultPlateNumberAttribute' => '\Wheel\Concept\MyCarBrandDefaultPlateNumberAttribute',
        '\Car\Producer\DefaultPlateNumberAttribute' => '\Wheel\Concept\MyCarProducerDefaultPlateNumberAttribute',
    ];

}
