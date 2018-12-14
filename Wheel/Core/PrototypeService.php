<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 12. 14.
 * Time: 17:41
 */

namespace Wheel\Core;

use Wheel\Auto\PrototypeService as BasePrototypeService;


class PrototypeService extends BasePrototypeService
{
    public static function init()
    {
        self::$assignments['\Car\CarProxy'] = '\Wheel\Concept\MyCarRubric';
        self::$assignments['\Car\Car\PlateNumberAttribute'] = '\Wheel\Concept\MyCarCarPlateNumberAttribute';
        self::$assignments['\Car\Brand\DefaultPlateNumberAttribute'] = '\Wheel\Concept\MyCarCarPlateNumberAttribute';
        self::$assignments['\Car\Producer\DefaultPlateNumberAttribute'] = '\Wheel\Concept\MyCarCarPlateNumberAttribute';
    }
}
